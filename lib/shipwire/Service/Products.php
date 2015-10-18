<?php

namespace Shipwire\Service;
use Shipwire\Database;
use Shipwire\Model\Product;

/**
 * Class Products
 * Service class to handle saving and retrieving Product objects
 * @package Shipwire\Service
 */
class Products extends Service
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
     * Query all the rows
     * @return Product[]
     */
    public function loadAll()
    {
        $sql = "
            SELECT
                product_id id, name, width_inches widthInches, length_inches lengthInches, depth_inches depthInches, weight_lbs weightLbs
            FROM
                products
        ";

        $statement = Database::getInstance()->prepare($sql);
        $statement->execute();
        return $this->convertToObjects($statement->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * @param $warehouseId
     * @return Product[]
     */
    public function loadForWarehouse($warehouseId)
    {
        $sql = "
            SELECT
                name, width_inches widthInches, length_inches lengthInches, depth_inches depthInches, weight_lbs weightLbs
            FROM
                products
            JOIN
                warehouse_products USING (product_id)
            WHERE
                warehouse_id = :warehouseId
        ";

        $statement = Database::getInstance()->prepare($sql);
        $statement->bindParam('warehouseId', $warehouseId);
        $statement->execute();
        return $this->convertToObjects($statement->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * @param $orderId
     * @return Product[]
     */
    public function loadForOrder($orderId)
    {
        $sql = "
            SELECT
                name, width_inches widthInches, length_inches lengthInches, depth_inches depthInches, weight_lbs weightLbs
            FROM
                products
            JOIN
                order_products USING (product_id)
            WHERE
                order_id = :orderId
        ";

        $statement = Database::getInstance()->prepare($sql);
        $statement->bindParam('orderId', $orderId);
        $statement->execute();
        return $this->convertToObjects($statement->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * @param Product $modelObject
     * @return bool
     */
    protected function insert($modelObject)
    {
        $sql = "
            INSERT INTO products
                (name, width_inches, length_inches, depth_inches, weight_lbs)
            VALUES
                (:name, :widthInches, :lengthInches, :depthInches, :weightLbs)
        ";

        $statement = Database::getInstance()->prepare($sql);
        $modelObject->bindParams($statement);
        return $statement->execute();
    }

    /**
     * @param Product $modelObject
     * @return bool
     */
    protected function update($modelObject)
    {
        $sql = "
            UPDATE
                products
            SET
                name = :name, width_inches = :widthInches, length_inches = :lengthInches,
                depth_inches = :depthInches, weight_lbs = :weightLbs
            WHERE
                productId = :id
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
        $sql = "DELETE FROM products WHERE id = :id";
        $statement = Database::getInstance()->prepare($sql);
        $statement->bindParam('id', $id);
        return $statement->execute();
    }

    /**
     * Convert associative array to array of Products
     * @param array $items
     * @return Product[]
     */
    protected function convertToObjects($items)
    {
        $results = array();
        foreach ($items as $item) {
            $results[] = new Product($item);
        }
        return $results;
    }
}