<?php
require __DIR__.'/vendor/autoload.php';

use App\Tools\ClassNameByFIle;
use App\Tools\FindFilesRecursivelyInDirectory;
use Symfony\Component\Console\Application;

define('ROOT_PATH', __DIR__);

$application = new Application();

$files = (new FindFilesRecursivelyInDirectory(ROOT_PATH . '/app/Commands'))->find(['php']);

foreach($files as $file){
    $classes = ClassNameByFIle::fromFile($file);
    foreach ($classes as $class){
        $application->add(new $class());
    }
}

$application->run();
