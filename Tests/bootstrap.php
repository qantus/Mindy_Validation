<?php

if(is_dir(__DIR__ . '/../vendor')) {
    include(__DIR__ . '/../vendor/autoload.php');
} else {
    require __DIR__ . '/../src.php';
}

require __DIR__ . '/TestCase.php';

function d() {
    $debug = debug_backtrace();
    $args = func_get_args();
    $data = array(
        'data' => $args,
        'debug' => array(
            'file' => $debug[0]['file'],
            'line' => $debug[0]['line'],
        )
    );
    if(class_exists('Mindy\Helper\Dumper')) {
        Mindy\Helper\Dumper::dump($data, 10);
    } else {
        var_dump($data);
    }
    die();
}
