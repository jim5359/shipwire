<?php
namespace Shipwire\Model;

/**
 * Class Model
 * Abstract model class representing a base model object.
 * @package shipwire\Model
 */
abstract class Model extends \ArrayObject
{
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Model
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param \PDOStatement $statement
     * @return Model
     */
    public abstract function bindParams($statement);
}