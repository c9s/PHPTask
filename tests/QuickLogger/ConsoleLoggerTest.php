<?php

class ConsoleLoggerTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {   
        $logger = new LightLogger\ConsoleLogger;
        ok($logger);
    }
}

