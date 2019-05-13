<?php
spl_autoload_register(function ($className) {
  // 把类都放在这里加载 todo
  $dir = 'src' . DIRECTORY_SEPARATOR;
  $registerMap = [
    // geo
    'TencentCloudBase\Database\Geo\Point' => $dir . 'database' . DIRECTORY_SEPARATOR . 'geo' . DIRECTORY_SEPARATOR . 'point',
    'TencentCloudBase\Database\Geo\LineString' => $dir . 'database' . DIRECTORY_SEPARATOR . 'geo' . DIRECTORY_SEPARATOR . 'lineString',
    'TencentCloudBase\Database\Geo\MultiLineString' => $dir . 'database' . DIRECTORY_SEPARATOR . 'geo' . DIRECTORY_SEPARATOR . 'multiLineString',
    'TencentCloudBase\Database\Geo\MultiPoint' => $dir . 'database' . DIRECTORY_SEPARATOR . 'geo' . DIRECTORY_SEPARATOR . 'multiPoint',
    'TencentCloudBase\Database\Geo\MultiPolygon' => $dir . 'database' . DIRECTORY_SEPARATOR . 'geo' . DIRECTORY_SEPARATOR . 'multiPolygon',
    'TencentCloudBase\Database\Geo\Polygon' => $dir . 'database' . DIRECTORY_SEPARATOR . 'geo' . DIRECTORY_SEPARATOR . 'polygon',
    // commands
    'TencentCloudBase\Database\Commands\LogicCommand' => $dir . 'database' . DIRECTORY_SEPARATOR . 'commands' . DIRECTORY_SEPARATOR . 'logic',
    'TencentCloudBase\Database\Commands\QueryCommand' => $dir . 'database' . DIRECTORY_SEPARATOR . 'commands' . DIRECTORY_SEPARATOR . 'query',
    'TencentCloudBase\Database\Commands\UpdateCommand' => $dir . 'database' . DIRECTORY_SEPARATOR . 'commands' . DIRECTORY_SEPARATOR . 'update',
    // exception
    'TencentCloudBase\Utils\TcbException' => $dir . 'utils' . DIRECTORY_SEPARATOR . 'exception',
    // regexp
    'TencentCloudBase\Database\Regexp\RegExp' => $dir . 'database' . DIRECTORY_SEPARATOR . 'regexp' . DIRECTORY_SEPARATOR . 'index',
    // serverDate
    'TencentCloudBase\Database\ServerDate\ServerDate' => $dir . 'database' . DIRECTORY_SEPARATOR . 'serverDate' . DIRECTORY_SEPARATOR . 'index',
    // database utils
    'TencentCloudBase\Database\Utils\Format' => $dir . 'database' . DIRECTORY_SEPARATOR . 'utils' . DIRECTORY_SEPARATOR . 'dataFormat',
    // collection
    'TencentCloudBase\Database\CollectionReference' =>  $dir . 'database' . DIRECTORY_SEPARATOR . 'collection',
    // command
    'TencentCloudBase\Database\Command' =>  $dir . 'database' . DIRECTORY_SEPARATOR . 'command',
    // consts
    'TencentCloudBase\Database\Constants' => $dir . 'database' . DIRECTORY_SEPARATOR . 'constants',
    // db
    'TencentCloudBase\Database\Db' =>  $dir . 'database' . DIRECTORY_SEPARATOR . 'db',
    // document
    'TencentCloudBase\Database\DocumentReference' =>  $dir . 'database' . DIRECTORY_SEPARATOR . 'document',
    // query
    'TencentCloudBase\Database\Query' =>  $dir . 'database' . DIRECTORY_SEPARATOR . 'query',
    // util
    'TencentCloudBase\Database\Util' =>  $dir . 'database' . DIRECTORY_SEPARATOR . 'util',
    // validate
    'TencentCloudBase\Database\Validate' =>  $dir . 'database' . DIRECTORY_SEPARATOR . 'validate',
    // functions
    'TencentCloudBase\Functions\TcbFunctions' => $dir . 'functions' . DIRECTORY_SEPARATOR . 'index',
    // storage
    'TencentCloudBase\Storage\TcbStorage' => $dir . 'storage' . DIRECTORY_SEPARATOR . 'index',
    // utils 
    'TencentCloudBase\Utils\Auth' => $dir . 'utils' . DIRECTORY_SEPARATOR . 'auth',
    'TencentCloudBase\Utils\TcbBase' => $dir . 'utils' . DIRECTORY_SEPARATOR . 'base',
    'TencentCloudBase\Utils\Request' => $dir . 'utils' . DIRECTORY_SEPARATOR . 'dbRequest',
    'TencentCloudBase\Utils\TcbException' => $dir . 'utils' . DIRECTORY_SEPARATOR . 'exception',
    // index
    'TencentCloudBase\TCB' => $dir . 'index',
    // code
    'TencentCloudBase\Consts\Code' => $dir . 'consts' . DIRECTORY_SEPARATOR . 'code',


  ];

  if (isset($registerMap[$className])) {
    $classFile = $registerMap[$className];
    if (is_file($classFile . '.php')) {
      require_once $classFile . '.php';
    }
  }
});
