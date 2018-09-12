<?php
/**
 * Created by PhpStorm.
 * User: Andrii
 * Date: 11.07.2018
 * Time: 14:58
 */

namespace vendor\core;


trait TSingleton
{
    protected static $instance;

    public static function instance()
    {
        if(self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}