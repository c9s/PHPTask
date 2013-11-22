<?php
namespace QuickLogger;

interface Loggable {
    public function info($msg);
    public function debug($msg);
    public function warn($msg);
    public function error($msg);
    public function fatal($msg);
    public function notice($msg);
}

