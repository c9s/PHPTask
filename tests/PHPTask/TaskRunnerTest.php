<?php

class TaskRunnerTest extends PHPUnit_Framework_TestCase
{
    public function testRunner()
    {
        $logger = new QuickLogger\ConsoleLogger;
        $runner = new \PHPTask\TaskRunner;
        $runner->setLogger($logger);
    }
}
