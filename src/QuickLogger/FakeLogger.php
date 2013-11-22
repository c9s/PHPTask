<?php
namespace QuickLogger;

/**
 * FakeLogger provides fake logger interface. it does not do anything.
 */
class FakeLogger implements Loggable
{
    public function info($msg) { }
    public function warn($msg) { }
    public function error($msg) { }
    public function debug($msg) { }
    public function fatal($msg) { }
}

