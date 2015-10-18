<?php
namespace Shipwire\Prompts;
use Shipwire\Model\Product;
use Shipwire\Model\Warehouse;
use Shipwire\Service\Products;
use Shipwire\Service\Warehouses;


/**
 * Class AddProductStock
 * @package Shipwire\Prompts
 */
class AddProductStock extends Prompts
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
        $this->_products = Products::getInstance()->loadAll();
        $this->_warehouses = Warehouses::getInstance()->loadAll();

        $warehouseSelections = '';
        $this->_validResponses['warehouseNum'] = array();
        foreach ($this->_warehouses as $index => $warehouse) {
            $selectNum = $index + 1;
            $this->_validResponses['warehouseNum'][] = $selectNum;
            $warehouseSelections .= "\t$selectNum) " . $warehouse->getName() . "\n";
        }
        $warehouseSelections .= 'Please enter your choice: ';

        $productSelections = '';
        $this->_validResponses['productNum'] = array();
        foreach ($this->_products as $index => $product) {
            $selectNum = $index + 1;
            $this->_validResponses['productNum'][] = $selectNum;
            $productSelections .= "\t$selectNum) " . $product->getName() . "\n";
        }
        $productSelections .= 'Please enter your choice: ';

        $this->_prompts = array(
            'warehouseNum' => "Please select a warehouse:\n" . $warehouseSelections,
            'productNum' => "Please select a product:\n" . $productSelections,
        );
    }

    /**
     * @param array $results
     */
    protected function finish($results)
    {
        $warehouse = $this->_warehouses[$results['warehouseNum'] - 1];
        $product = $this->_products[$results['productNum'] - 1];
        $warehouse->addProduct($product);
        Warehouses::getInstance()->save($warehouse);
        echo "Product added to warehouse stock successfully.\n";
    }
}
