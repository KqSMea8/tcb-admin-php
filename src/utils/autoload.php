<?php

spl_autoload_register(function($className) {
    $dir = 'src'.DIRECTORY_SEPARATOR;
    $registerMap = [
        'Tcb\TcbException' => $dir.'utils'.DIRECTORY_SEPARATOR.'exception',
        'Tcb\Database\Geo\Point' => $dir.'database'.DIRECTORY_SEPARATOR.'geo'.DIRECTORY_SEPARATOR.'point',
        'Tcb\Database\ServerData' => $dir.'database'.DIRECTORY_SEPARATOR.'serverData'.DIRECTORY_SEPARATOR.'index'
        // 'Tcb\Database\Command' => $dir.'database'.DIRECTORY_SEPARATOR.'command',
    ];


    if (isset($registerMap[$className])) {
        $classFile = $registerMap[$className];
    
        if (is_file($classFile.'php')) {
            require_once $classFile.'php';
        }
    }
});

?>