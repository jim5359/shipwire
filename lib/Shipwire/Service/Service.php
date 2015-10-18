<?php

namespace Shipwire\Service;
use Shipwire\Model;

abstract class Service
{

    /**
     * @param Model $modelObject
     * @return bool
     */
    public function save($modelObject)
    {
        if ($modelObject->getId()) {
            return $this->update($modelObject);
        } else {
            return $this->insert($modelObject);
        }
    }

    /**
     * @param Model $modelObject
     * @return bool
     */
    protected abstract function insert($modelObject);

    /**
     * @param Model $modelObject
     * @return bool
     */
    protected abstract function update($modelObject);

    /**
     * @param int $id
     * @return bool
     */
    protected abstract function delete($id);

    /**
     * @param array $items
     * @return Model[]
     */
    protected abstract function convertToObjects($items);
}