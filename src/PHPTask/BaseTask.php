<?php
namespace PHPTask;
use LightLogger\Loggable;

abstract class BaseTask {
    public $desc;

    public $stash = array();

    public $config;

    public $logger;

    public function __construct($config = array())
    {
        $this->config = $config;
    }

    public function setLogger(Loggable $logger) 
    {
        $this->logger = $logger;
    }


    public function __call($m, $a) {
        if ( $this->logger && method_exists($this->logger, $m) ) {
            return call_user_func_array(array($this->logger, $m ), $a);
        }
    }

    public function getDesc() {
        return $this->desc;
    }

    public function getName() {
        return get_class($this);
    }

    public function getConfig() {
        return $this->config;
    }

    public function __get($key)
    {
        if ( isset($this->stash[$key]) ) {
            return $this->stash[$key];
        }
    }

    public function __isset($key) 
    {
        return isset($this->stash[$key]);
    }

    public function __set($key, $value) {
        $this->stash[ $key ] = $value;
    }

    public function config($key) {
        if ( isset($this->config[$key]) ) {
            return $this->config[$key];
        }
    }

    abstract public function run();
}

