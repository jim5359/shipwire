<?php
namespace Shipwire\Prompts;
use Shipwire\Model\Warehouse;
use Shipwire\Service\Warehouses;


/**
 * Class AddWarehouse
 * @package Shipwire\Prompts
 */
class AddWarehouse extends Prompts
{
    /**
     * @var AddWarehouse
     */
    protected static $_instance;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_prompts = array(
            'name' => 'Please enter the warehouse name: ',
            'streetAddress' => 'Please enter the warehouse street address: ',
            'city' => 'Please enter the warehouse city: ',
            'state' => 'Please enter the warehouse state: ',
            'postalCode' => 'Please enter the warehouse postal code: ',
        );
    }

    /**
     * @param array $results
     */
    protected function finish($results)
    {
        $warehouse = new Warehouse($results);
        Warehouses::getInstance()->save($warehouse);
        echo "Warehouse added successfully.\n";
    }
}
