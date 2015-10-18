<?php
namespace Shipwire\Prompts;
use Shipwire\Model\Order;
use Shipwire\Service\Orders;


/**
 * Class AssignWarehouse
 * @package Shipwire\Prompts
 */
class AssignWarehouse extends Prompts
{
    /**
     * @var AssignWarehouse
     */
    protected static $_instance;

    /**
     * @var Order[]
     */
    protected $_orders;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_orders = Orders::getInstance()->loadAll();

        $orderSelections = '';
        $this->_validResponses['orderNum'] = array();
        foreach ($this->_orders as $index => $order) {
            $selectNum = $index + 1;
            $this->_validResponses['orderNum'][] = $selectNum;
            $orderSelections .= "\t$selectNum) " . $order->getFullAddress() . "\n";
        }
        $orderSelections .= 'Please enter your choice: ';

        $this->_prompts = array(
            'orderNum' => "Please select an order:\n" . $orderSelections,
        );
    }

    /**
     * @param array $results
     */
    protected function finish($results)
    {
        $order = $this->_orders[$results['orderNum'] - 1];

        if (!Orders::getInstance()->assignWarehouse($order)) {
            echo "No warehouses have enough stock for this order!\n";
        } else {
            echo "Warehouse assigned successfully.\n";
        }
    }
}
