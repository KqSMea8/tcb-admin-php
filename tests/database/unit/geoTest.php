<?php
require_once "index.php";
// require_once "tests/config/index.php";

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\Exception;

class GeoTest extends TestCase
{

  private static $tcb;

  private static $db;

  private static $databaseConfig;

  private static $_;

  private static $collName;

  private static $collection;

  private static $initialData;

  private static $longitude;

  private static $latitude;

  private static function is_orderArr($arr)
  {
    if (!is_array($arr)) {
      return false;
    }
    return array_keys($arr) === range(0, count($arr) - 1);
  }

  public static function getMilTimeseconds()
  {
    return (int)(microtime(true) * 1000);
  }

  public static function setUpBeforeClass()
  {
    global $TcbConfig;
    $TcbConfig = array("secretId" => "AKIDkOrrlYnf2ERxNeyna9Zowq4A4mNnl63p", "secretKey" => "juAIG5jCSXQAi9PUN3ax9Bj6HONDuQjq", 'env' => 'tcbenv-mPIgjhnq'); // 
    // $TcbConfig = array("secretId" => "AKIDY3Ws27uiEg0CC1QEg4GJCvWZUFrJhw66", "secretKey" => "2xiKmx1tdEhy76tVvJWggU7ZYP5cyCHO");
    self::$tcb = new Tcb($TcbConfig);
    self::$db = self::$tcb->getDatabase();
    self::$_ = self::$db->command;
    self::$collName = "coll-1";
    self::$collection = self::$db->collection(self::$collName);
    self::$longitude = -180;
    self::$latitude = 20;
    $point = self::$db->Point(self::$longitude, self::$latitude);
    $date = self::getMilTimeseconds();
    $date = 1557371491473;

    self::$initialData = [
      'point' => $point,
      'pointArr' => [$point, $point, $point],
      'uuid' => '416a4700-e0d3-11e8-911a-8888888888',
      'string' => '新增单个string字段1。新增单个string字段1。fsdfsafsd',
      'due' => $date,
      'int' => 100,
      'geo' => self::$db->Point(90, 23),
      'array' => [['string' => '99999999', 'due' => $date, 'geo' => self::$db->Point(90, 23)], ['string' => '0000000', 'geo' => self::$db->Point(90, 23), 'null' => null]]
    ];
  }

  public function testGeoPointCRUD()
  {
    $_ = self::$_;
    // $data = self::$db->Point(23, -67);

    // create
    $result = self::$collection->add(self::$initialData);
    $this->assertEquals(isset($result['id']) && isset($result['requestId']), true);

    // update
    $res = self::$collection->doc($result['id'])->set(self::$initialData);
    $this->assertEquals($res['updated'], 1);
    $this->assertEquals(isset($res['requestId']), true);

    // read
    $res = self::$collection->where(['_id' => $result['id']])->get();
    $this->assertEquals(count($res['data']) > 0, true);
    $this->assertEquals($res['data'][0]['point']->longitude === self::$longitude && $res['data'][0]['point']->latitude === self::$latitude, true);

    //delete
    $deleteRes = self::$collection->where(['_id' => $result['id']])->remove();
    $this->assertEquals($deleteRes['deleted'], 1);
  }
}
