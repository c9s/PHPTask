<?php

class FooTask extends PHPTask\BaseTask
{
    public function run() {

    }
}

class TaskRunnerTest extends PHPUnit_Framework_TestCase
{


    public function runnerProvider() 
    {
        $logger = new \LightLogger\FakeLogger;
        $runner = new \PHPTask\TaskRunner(array(  
            'logger' => $logger,
            'namespaces' => array(),
        ));
        return array(
            array($runner)
        );
    }


    /**
     * @dataProvider runnerProvider
     */
    public function testAddTask($runner)
    {
        $runner->addTask(new FooTask);
        $runner->run();
    }


    /**
     * @dataProvider runnerProvider
     */
    public function testEvalTask($runner)
    {
        $task = $runner->evalTask('FooTask');
        ok($task);
        $runner->addTask($task);
        is(1, $runner->run());
    }



}

