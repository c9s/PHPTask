<?php
namespace PHPTask;
use IteratorAggregate;
use ArrayIterator;

class TaskRunner implements IteratorAggregate {

    public $tasks = array();

    /**
     * TODO: base config across tasks.
     */
    public $config = array();

    public $logger;

    public function __construct($config = array()) {
        $this->config = $config;
        if ( isset($config['Tasks']) ) {
            $this->loadTasks($config['Tasks']);
        }
    }

    public function __call($m, $a) {
        if ( method_exists($this->logger, $m) ) {
            return call_user_func_array(array($this->logger, $m ), $a);
        }
    }

    public function loadTasks($tasks) {
        foreach( $tasks as $taskParam ) {
            if ( is_array($taskParam) ) {
                foreach( $taskParam as $taskClass => $taskConfig ) {
                    $this->info('Loading task ' . $taskClass);
                    $taskConfig = $taskConfig ? array_merge($this->config, $taskConfig) : $this->config;
                    $task = $this->loadTask($taskClass,$taskConfig);
                    $this->addTask($task);
                }
            } else if ( is_string($taskParam) ) {
                $task = $this->loadTask($taskParam,$this->config);
                $this->addTask($task);
            }
        }
    }

    public function loadTask($taskName, $taskConfig) {
        if ( class_exists($taskName, true) ) {
            return new $taskName($taskConfig);
        } else {
            $class = 'Corneltek\\Preview\\' . $taskName;
            if ( class_exists($class, true) ) {
                return new $class($taskConfig);
            }
            $class = 'Corneltek\\Preview\\Task\\' . $taskName;
            if ( class_exists($class, true) ) {
                return new $class($taskConfig);
            }
        }
        throw new Exception("Task $taskName can not be loaded.");
    }

    public function addTask($task) {
        $this->tasks[] = $task;
    }

    public function run() {
        foreach( $this->tasks as $task ) {
            $this->info("Running task " . $task->getName() . (($desc = $task->getDesc()) ? ":$desc" : "") );
            if ( false === $task->run() ) {
                $this->error( "Task " . $task->getName() . " failed.");
                break;
            }
        }
    }

    public function getIterator() {
        return new ArrayIterator($this->tasks);
    }
}
