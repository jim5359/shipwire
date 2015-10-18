<?php
namespace Shipwire\Prompts;
use Shipwire\Model\Product;
use Shipwire\Service\Products;


/**
 * Class AddProduct
 * @package Shipwire\Prompts
 */
class AddProduct extends Prompts
{
    /**
     * @var AddProduct
     */
    protected static $_instance;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_prompts = array(
            'name' => 'Please enter the product name: ',
            'widthInches' => 'Please enter the product width in inches: ',
            'lengthInches' => 'Please enter the product length in inches: ',
            'depthInches' => 'Please enter the product depth in inches: ',
            'weightLbs' => 'Please enter the product weight in lbs: ',
        );
    }

    /**
     * @param array $results
     */
    protected function finish($results)
    {
        $product = new Product($results);
        Products::getInstance()->save($product);
        echo "Product added successfully.\n";
    }
}
