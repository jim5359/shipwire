<?php

namespace Shipwire\Service;
use Shipwire\Database;
use Shipwire\Model\Warehouse;
use Shipwire\Service\Products;

/**
 * Class Warehouses
 * Service class to handle saving and retrieving Warehouse objects
 * @package Shipwire\Service
 */
class Warehouses extends Service
{
    protected static $instance;

    /**
     * Return a singleton instance of this service
     *
     * @return Warehouses
     */
    public static function getInstance() {
        if (!static::$instance) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    /**
     * @param Warehouse $modelObject
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
     * @return Warehouse[]
     */
    public function loadAll()
    {
        $sql = "
            SELECT
                warehouse_id id, name, street_address streetAddress, city, state, postal_code postalCode, longitude, latitude
            FROM
                warehouses
        ";

        $statement = Database::getInstance()->prepare($sql);
        $statement->execute();
        $warehouses = $this->convertToObjects($statement->fetchAll(\PDO::FETCH_ASSOC));
        foreach ($warehouses as &$warehouse) {
            $warehouse->setProducts(Products::getInstance()->loadForWarehouse($warehouse->getId()));
        }
        return $warehouses;
    }

    /**
     * @param Warehouse $modelObject
     * @return bool
     */
    protected function insert($modelObject)
    {
        $sql = "
            INSERT INTO warehouses
                (name, street_address, city, state, postal_code, longitude, latitude)
            VALUES
                (:name, :streetAddress, :city, :state, :postalCode, :longitude, :latitude)
        ";

        $statement = Database::getInstance()->prepare($sql);
        $modelObject->bindParams($statement);
        return $statement->execute();
    }

    /**
     * @param Warehouse $modelObject
     * @return bool
     */
    protected function update($modelObject)
    {
        $sql = "
            UPDATE
                warehouses
            SET
                name = :name, street_address = :streetAddress, city = :city, state = :state,
                postal_code = :postalCode, longitude = :longitude, latitude = :latitude
            WHERE
                warehouse_id = :id
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
        $sql = "DELETE FROM warehouses WHERE id = :id";
        $statement = Database::getInstance()->prepare($sql);
        $statement->bindParam('id', $id);
        return $statement->execute();
    }

    /**
     * @param Warehouse $modelObject
     * @return bool
     */
    protected function saveAddedProducts($modelObject) {
        $success = true;

        $warehouseId = $modelObject->getId();
        foreach ($modelObject->getAddedProducts() as $addedProduct) {
            $sql = "
                INSERT INTO warehouse_products
                    (warehouse_id, product_id)
                VALUES
                    (:warehouseId, :productId)
            ";

            $statement = Database::getInstance()->prepare($sql);
            $statement->bindParam('warehouseId', $warehouseId);
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
     * Convert associative array to array of Products
     * @param array $items
     * @return Warehouse[]
     */
    protected function convertToObjects($items)
    {
        $results = array();
        foreach ($items as $item) {
            $results[] = new Warehouse($item);
        }
        return $results;
    }

}