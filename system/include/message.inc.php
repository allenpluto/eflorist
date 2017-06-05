<?php
// Include Class Object
// Name: message
// Description: use preference class to control global variables

class message
{
    private $property = array();
    private static $instance;

    private function __construct() {}

    public static function get_instance()
    {
        if (empty(self::$instance))
        {
            self::$instance = new message();
        }
        return self::$instance;
    }

    public function __set($key, $value)
    {
        if (!isset($this->property[$key])) $this->property[$key] = array();
        $this->property[$key][] = $value;
    }

    public function __get($key)
    {
        if (!isset($this->property[$key])) return false;
        return $this->property[$key];
    }

    public function display()
    {
        return $this->property;
    }
}