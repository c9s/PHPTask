<?php
namespace QuickLogger;

class ConsoleLogger implements Loggable
{
    public function info($msg) {
        echo "INFO - " , $msg , "\n";
    }
    public function warn($msg) {
        echo "WARN - " , $msg , "\n";
    }

    public function notice($msg) {
        echo "NOTICE - " , $msg , "\n";
    }

    public function error($msg) { 
        echo "ERROR - " , $msg , "\n";
    }
    public function debug($msg) { 
        echo "DEBUG - " , $msg , "\n";
    }
    public function fatal($msg) { 
        echo "FATAL - " , $msg , "\n";
    }
}



