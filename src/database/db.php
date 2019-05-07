<?php
require_once "src/utils/base.php";
// require_once "src/database/serverDate/index.php";
require_once "src/database/collection.php";
require_once "src/database/command.php";
require_once "src/database/regexp/index.php";
require_once "src/database/serverDate/index.php";

// require_once 'src/consts/code.php';
require_once 'src/utils/dbRequest.php';
require_once 'src/database/utils/dataFormat.php';

require_once "src/database/geo/lineString.php";
require_once "src/database/geo/multiLineString.php";
require_once "src/database/geo/multiPoint.php";
require_once "src/database/geo/multiPolygon.php";
require_once "src/database/geo/polygon.php";
require_once "src/database/geo/point.php";


use Tcb\Geo\LineString\LineString;
use Tcb\Geo\MultiLineString\MultiLineString;
use Tcb\Geo\Point\Point;
use Tcb\Geo\MultiPoint\MultiPoint;
use Tcb\Geo\Polygon\Polygon;
use Tcb\Geo\MultiPolygon\MultiPolygon;

use Tcb\ServerDate\ServerDate;
use Tcb\RegExp\RegExp;

// use Tcb\Collection\CollectionReference;
// use Tcb\Command\Command;
use Tcb\TcbException;

class Db
{

  public $config;
  public $command;

  function __construct($config)
  {
    // parent::__construct($config);
    $this->config = $config;
    $this->command = new Command();
  }

  /**
   * 获取serverDate对象
   *
   */
  public function serverDate($options = ["offset" => 0])
  {
    $offset = $options["offset"];
    return new ServerDate(["offset" => $offset]);
  }

  /**
   * 获取RegExp对象
   *
   */
  public function RegExp($opts = ['regexp' => '', 'options' => ''])
  {
    return new RegExp($opts);
  }

  /**
   * 获取RegExp对象
   *
   */
  public function Point($longitude, $latitude)
  {
    return new Point($longitude, $latitude);
  }


  /**
   * 获取RegExp对象
   *
   */
  public function MultiPoint($points = [])
  {
    return new MultiPoint($points);
  }

  /**
   * 获取RegExp对象
   *
   */
  public function LineString($points = [])
  {
    return new LineString($points);
  }

  /**
   * 获取RegExp对象
   *
   */
  public function MultiLineString($lines = [])
  {
    return new MultiLineString($lines);
  }

  /**
   * 获取RegExp对象
   *
   */
  public function Polygon($lines = [])
  {
    return new Polygon($lines);
  }

  /**
   * 获取RegExp对象
   *
   */
  public function MultiPolygon($polygons = [])
  {
    return new MultiPolygon($polygons);
  }

  /**
   * 获取集合的引用
   *
   * @param collName - 集合名称
   */
  public function collection($collName = null)
  {
    if (!isset($collName)) {
      throw new TcbException(EMPTY_PARAM, "Collection name is required");
    }

    return new CollectionReference($this, $collName);
  }

  /**
   * 创建集合
   *
   * @param [String] $collName
   * @return Array
   */
  public function createCollection($collName = null)
  {
    // if (!isset($collName)) {
    //     throw new TencentCloudSDKException(EMPTY_PARAM, "Collection name is required");
    // }

    $request = new Request($this->config);

    $params = [
      "collectionName" => $collName
    ];

    $result = $request->send('database.addCollection', $params);

    return $result;
  }
}
