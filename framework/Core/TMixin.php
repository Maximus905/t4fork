<?php

namespace T4\Core;

use T4\Core\Mixin;

trait TMixin
{

    static protected $mixins = [];

    public function __call($name, $argv)
    {
        foreach (static::$mixins as $class) {

            if (!class_exists($class))
                throw new Exception('Class ' . $class . ' does not exist.');
            if (!($class instanceof Mixin))
                throw new Exception('Class ' . $class . ' is not a mixin.');

            $mixin = new $class;

            if (!is_callable([$mixin, $name]))
                throw new Exception('Method ' . $class . '::' . $name . ' is not callable.');

            $mixin->setCaller($this);
            return call_user_func_array([$mixin, $name], $argv);

        }

        throw new Exception('Method ' . $name . ' is not found in mixins');

    }

    public function __callStatic($name, $argv)
    {
        foreach (static::$mixins as $class) {

            if (!class_exists($class))
                throw new Exception('Class ' . $class . ' does not exist.');
            if (!($class instanceof Mixin))
                throw new Exception('Class ' . $class . ' is not a mixin.');

            if (!is_callable([$class, $name]))
                throw new Exception('Method ' . $class . '::' . $name . ' is not callable.');

            return call_user_func_array([$class, $name], $argv);

        }

        throw new Exception('Method ' . $name . ' is not found in mixins');

    }

}