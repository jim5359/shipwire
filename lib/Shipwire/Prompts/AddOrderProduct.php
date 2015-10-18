<?php
namespace Shipwire\Prompts;
use Shipwire\Model\Order;
use Shipwire\Model\Product;
use Shipwire\Service\Orders;
use Shipwire\Service\Products;


/**
 * Class AddProductStock
 * @package Shipwire\Prompts
 */
class AddOrderProduct extends Prompts
{
    /**
     * @var AddProductStock
     */
    protected static $_instance;

    /**
     * @var Order[]
     */
    protected $_orders;

    /**
     * @var Product[]
     */
    protected $_products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_orders = Orders::getInstance()->loadAll();
        $this->_products = Products::getInstance()->loadAll();

        $orderSelections = '';
        $this->_validResponses['orderNum'] = array();
        foreach ($this->_orders as $index => $order) {
            $selectNum = $index + 1;
            $this->_validResponses['orderNum'][] = $selectNum;
            $orderSelections .= "\t$selectNum) " . $order->getFullAddress() . "\n";
        }
        $orderSelections .= 'Please enter your choice: ';

        $productSelections = '';
        $this->_validResponses['productNum'] = array();
        foreach ($this->_products as $index => $product) {
            $selectNum = $index + 1;
            $this->_validResponses['productNum'][] = $selectNum;
            $productSelections .= "\t$selectNum) " . $product->getName() . "\n";
        }
        $productSelections .= 'Please enter your choice: ';

        $this->_prompts = array(
            'orderNum' => "Please select an order:\n" . $orderSelections,
            'productNum' => "Please select a product:\n" . $productSelections,
        );
    }

    /**
     * @param array $results
     */
    protected function finish($results)
    {
        $order = $this->_orders[$results['orderNum'] - 1];
        $product = $this->_products[$results['productNum'] - 1];
        $order->addProduct($product);
        Orders::getInstance()->save($order);
        echo "Product added to order successfully.\n";
    }
}
