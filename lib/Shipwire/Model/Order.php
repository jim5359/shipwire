<?php

namespace Shipwire\Model;

/**
 * Class Order
 * Model class representing an order object.
 * @package shipwire\Model
 */
class Order extends Address
{
    /**
     * @var Product[]
     */
    protected $_addedProducts = array();

    /*********************************************************************************/
    /**
     * Constructor: initializes all values
     *
     * @param array $data
     */
    public function __construct($data = array()) {
        parent::__construct(array_merge(array(
            'id' => null,
            'warehouseId' => null,
            'streetAddress' => null,
            'city' => null,
            'state' => null,
            'postalCode' => null,
            'longitude' => null,
            'latitude' => null,
            'products' => array(),
        ), $data), \ArrayObject::ARRAY_AS_PROPS);

        if (!empty($data) && (empty($data['longitude']) || empty($data['latitude']))) {
            $this->geoCodeAddress();
        }
    }

    /**
     * @return int
     */
    public function getWarehouseId()
    {
        return $this->warehouseId;
    }

    /**
     * @param int $warehouseId
     * @return Order
     */
    public function setWarehouseId($warehouseId)
    {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    /**
     * @return \Shipwire\Model\Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product $product
     */
    public function addProduct($product)
    {
        $this->products[] = $product;
        $this->_addedProducts[] = $product;
    }

    /**
     * @return Product[]
     */
    public function getAddedProducts() {
        return $this->_addedProducts;
    }

    /**
     * @param Products[] $addedProducts
     * @return Warehouse
     */
    public function setAddedProducts($addedProducts) {
        $this->_addedProducts = $addedProducts;
        return $this;
    }

    /**
     * @param \Shipwire\Model\Product[] $products
     * @return Order
     */
    public function setProducts($products)
    {
        $this->products = $products;
        return $this;
    }

    /**
     * Bind properties to a passed PDO Statement
     * @param \PDOStatement $statement
     * @return Warehouse
     */
    public function bindParams($statement) {
        $statement->bindParam(':warehouseId', $this->warehouseId);
        parent::bindParams($statement);
        return $this;
    }

}