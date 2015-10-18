<?php

namespace Shipwire\prompts;

/**
 * Class Prompts
 * @package Shipwire\Prompts
 */
abstract class Prompts
{
    /**
     * Array of prompts to display and wait for a response
     *
     * @var array
     */
    protected $_prompts = array();

    /**
     * Array of valid responses for each prompt
     *
     * @var array
     */
    protected $_validResponses = array();

    /**
     * Return a singleton instance of this service
     *
     * @return Prompts
     */
    public static function getInstance() {
        if (!static::$_instance) {
            static::$_instance = new static;
        }
        return static::$_instance;
    }

    public function display()
    {
        $results = array();
        foreach ($this->_prompts as $index => $prompt) {
            do {
                echo $prompt;
                $response = trim(fgets(STDIN));
                if (!empty($this->_validResponses[$index]) && !in_array($response, $this->_validResponses[$index])) {
                    $response = null;
                }
            } while (!$response);
            $results[$index] = $response;
        }
        $this->finish($results);
        echo "Press Enter to continue: ";
        fgets(STDIN);
    }

    protected abstract function finish($results);
}