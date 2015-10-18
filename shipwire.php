<?php
require_once 'lib\Shipwire\Bootstrap.php';
use Shipwire\Prompts;

$done = false;
do {
    echo "\n";
    echo "Welcome to the Shipwire Ordering System!\nPlease choose one of the following options:\n\n";
    echo "\t1) Add a warehouse\n";
    echo "\t2) Add a product\n";
    echo "\t3) Add product stock to a warehouse\n";
    echo "\t4) Create an order\n";
    echo "\t5) Add product to order\n";
    echo "\t6) Assign a warehouse to an order\n";
    echo "\tQ) Quit\n";


    echo "\nPlease enter your choice (1-6): ";
    $result = strtoupper(trim(fgets(STDIN)));

    switch ($result) {
        case 1:
            Prompts\AddWarehouse::getInstance()->display();
            break;
        case 2:
            Prompts\AddProduct::getInstance()->display();
            break;
        case 3:
            Prompts\AddProductStock::getInstance()->display();
            break;
        case 4:
            Prompts\AddOrder::getInstance()->display();
            break;
        case 5:
            Prompts\AddOrderProduct::getInstance()->display();
            break;
        case 6:
            Prompts\AssignWarehouse::getInstance()->display();
            break;
        case 'Q':
            $done = true;
            break;
        default:
            echo "Invalid option.  Press Enter to continue.";
            fgets(STDIN);
    }
} while (!$done);
