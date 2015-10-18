<?php

namespace Shipwire\Model;
use Shipwire\Model\Product;

/**
 * Class Warehouse
 * Model class representing a warehouse object.
 * @package shipwire\Model
 */
class Warehouse extends Address
{

    /**
     * @var Product[]
     */
    protected $_addedProducts = array();

    /**
     * Constructor: initializes all values
     *
     * @param array $data
     */
    public function __construct($data = array()) {
        parent::__construct(array_merge(array(
            'id' => null,
            'name' => null,
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Warehouse
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Product[]
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
     * @param Product[] $products
     * @return Warehouse
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
        $statement->bindParam(':name', $this->name);
        parent::bindParams($statement);
        return $this;
    }
}