<?php
namespace Shipwire\Prompts;
use Shipwire\Model\Order;
use Shipwire\Service\Orders;


/**
 * Class AddProductStock
 * @package Shipwire\Prompts
 */
class AddOrder extends Prompts
{
    /**
     * @var AddProductStock
     */
    protected static $_instance;

    /**
     * @var Product[]
     */
    protected $_products;

    /**
     * @var Warehouse[]
     */
    protected $_warehouses;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_prompts = array(
            'streetAddress' => 'Please enter the order street address: ',
            'city' => 'Please enter the order city: ',
            'state' => 'Please enter the order state: ',
            'postalCode' => 'Please enter the order postal code: ',
        );
    }

    /**
     * @param array $results
     */
    protected function finish($results)
    {
        $product = new Order($results);
        Orders::getInstance()->save($product);
        echo "Order added successfully.\n";
    }
}
