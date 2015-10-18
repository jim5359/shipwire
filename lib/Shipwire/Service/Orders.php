<?php

namespace Shipwire\Service;
use Shipwire\Database;
use Shipwire\Model\Order;

/**
 * Class Orders
 * Service class to handle saving and retrieving Order objects
 * @package Shipwire\Service
 */
class Orders extends Service
{
    protected static $instance;

    /**
     * Return a singleton instance of this service
     *
     * @return Products
     */
    public static function getInstance() {
        if (!static::$instance) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * @param Order $modelObject
     * @return bool
     */
    public function save($modelObject)
    {
        if (parent::save($modelObject)) {
            return $this->saveAddedProducts($modelObject);
        }
    }

    /**
     * Query all the rows
     * @return Order[]
     */
    public function loadAll()
    {
        $sql = "
            SELECT
                order_id id, warehouse_id warehouseId, street_address streetAddress, city, state, postal_code postalCode, longitude, latitude
            FROM
                orders
        ";

        $statement = Database::getInstance()->prepare($sql);
        $statement->execute();
        $orders = $this->convertToObjects($statement->fetchAll(\PDO::FETCH_ASSOC));
        foreach ($orders as &$order) {
            $order->setProducts(Products::getInstance()->loadForOrder($order->getId()));
        }
        return $orders;
    }

    /**
     * @param Order $modelObject
     * @return bool
     */
    protected function insert($modelObject)
    {
        $sql = "
            INSERT INTO orders
                (warehouse_id, street_address, city, state, postal_code, longitude, latitude)
            VALUES
                (:warehouseId, :streetAddress, :city, :state, :postalCode, :longitude, :latitude)
        ";

        $statement = Database::getInstance()->prepare($sql);
        $modelObject->bindParams($statement);
        return $statement->execute();
    }

    /**
     * @param Order $modelObject
     * @return bool
     */
    protected function update($modelObject)
    {
        $sql = "
            UPDATE
                orders
            SET
                warehouse_id = :warehouseId, street_address = :streetAddress, city = :city, state = :state,
                postal_code = :postalCode, longitude = :longitude, latitude = :latitude
            WHERE
                order_id = :id
        ";
        $statement = Database::getInstance()->prepare($sql);
        $id = $modelObject->getId();
        $statement->bindParam('id', $id);
        $modelObject->bindParams($statement);
        return $statement->execute();
    }

    /**
     * @param int $id
     * @return bool
     */
    protected function delete($id)
    {
        $sql = "DELETE FROM orders WHERE id = :id";
        $statement = Database::getInstance()->prepare($sql);
        $statement->bindParam('id', $id);
        return $statement->execute();
    }

    /**
     * @param Order $modelObject
     * @return bool
     */
    protected function saveAddedProducts($modelObject) {
        $success = true;

        $orderId = $modelObject->getId();
        foreach ($modelObject->getAddedProducts() as $addedProduct) {
            $sql = "
                INSERT INTO order_products
                    (order_id, product_id)
                VALUES
                    (:orderId, :productId)
            ";

            $statement = Database::getInstance()->prepare($sql);
            $statement->bindParam('orderId', $orderId);
            $productId = $addedProduct->getId();
            $statement->bindParam('productId', $productId);
            $success = $statement->execute();
            if (!$success) break;
        }
        if ($success) {
            $modelObject->setAddedProducts(array()); // Clear added products
        }
        return $success;
    }

    /**
     * Assign a warehouse based on stock availability and distance from order
     * @param Order $order
     * @return bool
     */
    public function assignWarehouse($order)
    {
        $warehouseId = $this->findWarehouse($order);
        if (!$warehouseId) {
            return false;
        }
        $order->setWarehouseId($warehouseId);
        Orders::getInstance()->save($order);
        return true;
    }


    protected function findWarehouse($order)
    {
        // Run query to get list of warehouses with product stock sorted by distance
        $sql = "
            SELECT
                od.product_id productId, order_qty orderQty, warehouse_id warehouseId, stock_qty stockQty,
                (((acos(sin((od.latitude*pi()/180)) * sin((wd.latitude*pi()/180))+cos((od.latitude*pi()/180)) * cos((wd.latitude*pi()/180)) * cos(((od.longitude-wd.longitude)*pi()/180))))*180/pi())*60*1.1515) distance
            FROM
                (
                    SELECT
                        o.order_id, p.product_id, count(op.product_id) order_qty, o.longitude, o.latitude
                    FROM
                        orders o
                    JOIN
                        products p
                    JOIN
                        order_products op
                            ON op.order_id = o.order_id
                            AND op.product_id = p.product_id
                    GROUP BY
                        o.order_id, p.product_id
                ) od
            LEFT JOIN
                (
                    SELECT
                        w.warehouse_id, p.product_id, count(wp.product_id) stock_qty, w.longitude, w.latitude
                    FROM
                        warehouses w
                    JOIN
                        products p
                    JOIN
                        warehouse_products wp
                            ON wp.warehouse_id = w.warehouse_id
                            AND wp.product_id = p.product_id
                    GROUP BY
                        w.warehouse_id, p.product_id
                ) wd
                ON wd.product_id = od.product_id AND wd.stock_qty >= od.order_qty
            WHERE
                order_id = :orderId
            ORDER BY
                distance
        ";
        $statement = Database::getInstance()->prepare($sql);
        $orderId = $order->getId();
        $statement->bindParam('orderId', $orderId);
        $statement->execute();
        $currWarehouseId = 0;
        $numProducts = 0;
        foreach($statement->fetchAll(\PDO::FETCH_ASSOC) as $candidate) {
            if (!$candidate['warehouseId']) {
                // No warehouse has adequate stock of this item
                return false;
            }
            if ($currWarehouseId != $candidate['warehouseId']) {
                $currWarehouseId = $candidate['warehouseId'];
                $numProducts = 0; // Last warehouse didn't have enough stock to fulfill order so reset numProducts
            }
            $numProducts += $candidate['orderQty'];
            if ($numProducts == count($order->getProducts())) {
                return $currWarehouseId; // We've found our warehouse
            }
        }
        return false; // No warehouse can fulfill order
    }

    /**
     * Convert associative array to array of Products
     * @param array $items
     * @return Order[]
     */
    protected function convertToObjects($items)
    {
        $results = array();
        foreach ($items as $item) {
            $results[] = new Order($item);
        }
        return $results;
    }
}