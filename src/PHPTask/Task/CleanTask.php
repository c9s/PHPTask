<?php
namespace PHPTask\Task;

class CleanTask extends BaseTask
{
    public function run() {
        if ($paths = $this->config('paths') ) {
            foreach( $paths as $path ){
                if ( file_exists($path) ) {
                    futil_rmtree($path);
                }
            }
        }
    }
}
