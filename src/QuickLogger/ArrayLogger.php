<?php
namespace QuickLogger;

class ArrayLogger implements Loggable
{
    public $messages = array();

    public function info($msg) {
        $this->push($msg, "info");
    }

    public function warn($msg) {
        $this->push($msg, "warn");
    }

    public function error($msg) { 
        $this->push($msg, "error");
    }

    public function debug($msg) { 
        $this->push($msg, "debug");
    }

    public function fatal($msg) { 
        $this->push($msg, "fatal");
    }

    public function push($msg, $level) { 
        $this->messages[] = array( $level, $msg , time() );
    }

    public function dump() {
        return $this->messages;
    }

    public function format($format) {
        $output = array();
        foreach( $this->messages as $msg) {
            $newMsg = $format;
            $newMsg = str_replace('%l', $msg[0], $newMsg); // replace level
            $newMsg = str_replace('%m', $msg[1], $newMsg);
            $newMsg = str_replace('%t', date(DateTime::ATOM, $msg[2]) , $newMsg);
            $output[] = $newMsg;
        }
        return $output;
    }
}

