<?php

namespace Shipwire\Model;

/**
 * Class Product
 * Model class representing a product object.
 * @package shipwire\Model
 */
class Product extends Model
{
    /**
     * Constructor: initializes all values
     *
     * @param array $data
     */
    public function __construct($data = array()) {
        parent::__construct(array_merge(array(
            'id' => null,
            'name' => null,
            'widthInches' => null,
            'lengthInches' => null,
            'depthInches' => null,
            'weightLbs' => null,
        ), $data), \ArrayObject::ARRAY_AS_PROPS);
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
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return float
     */
    public function getWidthInches()
    {
        return $this->widthInches;
    }

    /**
     * @param float $widthInches
     * @return Product
     */
    public function setWidthInches($widthInches)
    {
        $this->widthInches = $widthInches;
        return $this;
    }

    /**
     * @return float
     */
    public function getLengthInches()
    {
        return $this->lengthInches;
    }

    /**
     * @param float $lengthInches
     * @return Product
     */
    public function setLengthInches($lengthInches)
    {
        $this->lengthInches = $lengthInches;
        return $this;
    }

    /**
     * @return float
     */
    public function getDepthInches()
    {
        return $this->depthInches;
    }

    /**
     * @param float $depthInches
     * @return Product
     */
    public function setDepthInches($depthInches)
    {
        $this->depthInches = $depthInches;
        return $this;
    }

    /**
     * @return float
     */
    public function getWeightLbs()
    {
        return $this->weightLbs;
    }

    /**
     * @param float $weightLbs
     * @return Product
     */
    public function setWeightLbs($weightLbs)
    {
        $this->weightLbs = $weightLbs;
        return $this;
    }

    /**
     * Bind properties to a passed PDO Statement
     * @param \PDOStatement $statement
     * @return Product
     */
    public function bindParams($statement) {
        $statement->bindParam(':name', $this->name);
        $statement->bindParam(':widthInches', $this->widthInches);
        $statement->bindParam(':lengthInches', $this->lengthInches);
        $statement->bindParam(':depthInches', $this->depthInches);
        $statement->bindParam(':weightLbs', $this->weightLbs);
        return $this;
    }

}