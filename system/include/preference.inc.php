<?php
// Include Class Object
// Name: preference
// Description: use preference class to control global variables

class preference
{
    private $property = array();
    private static $instance;

    private function __construct() {}

    public static function get_instance()
    {
        if (empty(self::$instance))
        {
            self::$instance = new preference();
        }
        return self::$instance;
    }

    public function __set($key, $value)
    {
        $this->property[$key] = $value;
    }

    public function __get($key) {
        if (isset($this->property[$key])) return $this->property[$key];
        else return false;
    }
}