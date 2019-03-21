<?php
if (file_exists($file = __DIR__.'/../vendor/autoload.php')) {
    require_once $file;
} else {
    die("Please install dependencies using Composer to run the test suite. \n");
}
require_once __DIR__.'/BaseCase.php';