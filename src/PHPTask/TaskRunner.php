<?php
namespace PHPTask;
use IteratorAggregate;
use ArrayIterator;
use LightLogger\Loggable;

class TaskRunner implements IteratorAggregate 
{
    public $tasks = array();
    public $config = array();
    public $namespaces = array();

    public $logger;

    public function __construct($config = array()) {
        $this->config = $config;
        if ( isset($config['tasks']) ) {
            $this->evalTasks($config['tasks']);
        }
        if ( isset($config['logger']) ) {
            $this->setLogger($config['logger']);
        }
        if ( isset($config['namespaces']) ) {
            $this->namespaces = $config['namespaces'];
        }
    }

    public function setNamespaces(array $ns) {
        $this->namespaces = $ns;
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


    public function evalTasks($tasks) {
        foreach( $tasks as $taskParam ) {
            if ( is_array($taskParam) ) {
                foreach( $taskParam as $taskClass => $taskConfig ) {
                    $this->info('Loading task ' . $taskClass);
                    $taskConfig = $taskConfig ? array_merge($this->config, $taskConfig) : $this->config;
                    $this->addTask( $this->evalTask($taskClass,$taskConfig) );
                }
            } else if ( is_string($taskParam) ) {
                $this->addTask( $this->evalTask($taskParam,$this->config) );
            }
        }
    }

    /**
     * Create a task object from task name with parameters
     *
     * @param string $taskName task name
     * @param array $config task config
     * @return BaseTask
     * */
    public function evalTask($taskName, $config = array())
    {
        if ( isset($this->config['namespaces']) ) {
            foreach( $this->config['namespaces'] as $ns ) {
                $class = $ns . '\\' . $taskName;
                if ( class_exists($class, true) ) {
                    $task = new $class($config);
                    if ( $this->logger ) {
                        $task->setLogger($this->logger);
                    }
                    return $task;
                }
            }
        }

        if ( class_exists($taskName, true) ) {
            $task = new $taskName($config);
            if ( $this->logger ) {
                $task->setLogger($this->logger);
            }
            return $task;
        }
        throw new Exception("Task $taskName can not be loaded.");
    }


    /**
     * Push Task Object
     * */
    public function addTask($task) {
        $this->tasks[] = $task;
    }

    public function run() {
        $ranTasks = 0;
        foreach( $this->tasks as $task ) {
            $this->info("Running task " . $task->getName() . (($desc = $task->getDesc()) ? ":$desc" : "") );
            if ( false === $task->run() ) {
                $this->error( "Task " . $task->getName() . " failed.");
                break;
            }
            $ranTasks++;
        }
        return $ranTasks;
    }

    public function getIterator() {
        return new ArrayIterator($this->tasks);
    }
}
