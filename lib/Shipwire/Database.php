<?php
namespace Shipwire;

class Database extends \PDO
{
    protected static $instance;

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new static('mysql:host=localhost;dbname=shipwire', 'shipwire', 'shipwire');
            self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}