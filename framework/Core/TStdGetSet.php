<?php

namespace T4\Core;

trait TStdGetSet
{

    protected function innerIsSet($key)
    {
        return isset($this->__data[$key]) || method_exists($this, 'get' . ucfirst($key));
    }

    protected function innerUnSet($key)
    {
        unset($this->__data[$key]);
    }

    protected function innerGet($key)
    {
        $method = 'get' . ucfirst($key);
        if ( method_exists($this, $method) )
            return $this->$method();
        return isset($this->__data[$key]) ? $this->__data[$key] : null;
    }

    protected function innerSet($key, $val)
    {
        $method = 'set' . ucfirst($key);
        if ( method_exists($this, $method) )
            $this->$method($val);
        else
            $this->__data[$key] = $val;
    }

    /**
     * ArrayAccess implementation
     */
    public function offsetExists($offset)
    {
        return $this->innerIsSet($offset);
    }
    public function offsetUnset($offset)
    {
        $this->innerUnSet($offset);
    }
    public function offsetGet($offset)
    {
        return $this->innerGet($offset);
    }
    public function offsetSet($offset, $value)
    {
        $this->innerSet($offset, $value);
    }

    /*
     * "Magic" methods
     */
    public function __isset($key)
    {
        return $this->innerIsSet($key);
    }
    public function __unset($key)
    {
        $this->innerUnSet($key);
    }
    public function __get($key)
    {
        if (!$this->innerIsSet($key)) {
            $debug =  debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT, 1)[0];
            if ($debug['function'] == '__get' && $debug['object'] === $this && $debug['type'] == '->') {
                $property = $debug['args']['0'];
                $line = (file($debug['file'])[$debug['line']-1]);
                if (preg_match('~\-\>' . $property . '\-\>.+\=~', $line, $m)) {
                    $this->__data[$property] = new static;
                    return $this->__data[$property];
                }
            }
            return null;
        }
        return $this->innerGet($key);
    }
    public function __set($key, $val)
    {
        $this->innerSet($key, $val);
    }

} 