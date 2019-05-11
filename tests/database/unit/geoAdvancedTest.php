<?php
require_once "tests/autoload.php";

use PHPUnit\Framework\TestCase;

use TencentCloudBase\Database\Geo\LineString;
use TencentCloudBase\Database\Geo\MultiLineString;
use TencentCloudBase\Database\Geo\MultiPoint;
use TencentCloudBase\Database\Geo\MultiPolygon;
use TencentCloudBase\Database\Geo\Point;
use TencentCloudBase\Database\Geo\Polygon;

use TencentCloudBase\Utils\TcbException;
use TencentCloudBase\TCB;


class GeoAdvancedTest extends TestCase
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

  private static $point1;
  private static $point2;
  private static $point3;
  private static $point4;
  private static $point5;
  private static $point6;
  private static $point7;
  private static $point8;




  private static function randomPoint()
  {
    return self::$db->Point(180 - random_int(0, 360), 90 - random_int(0, 180));
  }

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
    $TcbConfig = array("secretId" => "AKIDkOrrlYnf2ERxNeyna9Zowq4A4mNnl63p", "secretKey" => "juAIG5jCSXQAi9PUN3ax9Bj6HONDuQjq"); // 
    // $TcbConfig = array("secretId" => "AKIDY3Ws27uiEg0CC1QEg4GJCvWZUFrJhw66", "secretKey" => "2xiKmx1tdEhy76tVvJWggU7ZYP5cyCHO");
    self::$tcb = new TCB($TcbConfig);
    self::$db = self::$tcb->getDatabase();
    self::$_ = self::$db->command;
    self::$collName = "coll-1";
    self::$collection = self::$db->collection(self::$collName);

    // 回字外环
    self::$point1 = self::$db->Point(-2, -2);
    self::$point2 = self::$db->Point(2, -2);
    self::$point3 = self::$db->Point(2, 2);
    self::$point4 = self::$db->Point(-2, 2);

    // 回字内环
    self::$point5 = self::$db->Point(-1, -1);
    self::$point6 = self::$db->Point(1, -1);
    self::$point7 = self::$db->Point(1, 1);
    self::$point8 = self::$db->Point(-1, 1);
  }

  // public function testCreateCollection()
  // {
  //   self::$db->createCollection(self::$collName);
  // }

  public function testGeoPointCRUD()
  {
    $db = self::$db;
    $geoNearPoint = $db->Point(0, 0);
    $line = $db->LineString([self::randomPoint(), self::randomPoint()]);

    // 回字外环
    $point1 = self::$point1;
    $point2 = self::$point2;
    $point3 = self::$point3;
    $point4 = self::$point4;

    // 回字内环
    $point5 = self::$point5;
    $point6 = self::$point6;
    $point7 = self::$point7;
    $point8 = self::$point8;


    $polygon = $db->Polygon([$db->LineString([$point1, $point2, $point3, $point4, $point1]), $db->LineString([$point5, $point6, $point7, $point8, $point5])]);
    $multiPoint = $db->MultiPoint([self::randomPoint(), self::randomPoint(), self::randomPoint(), self::randomPoint()]);
    $multiLineString = $db->MultiLineString([$db->LineString([self::randomPoint(), self::randomPoint()]), $db->LineString([self::randomPoint(), self::randomPoint()]), $db->LineString([self::randomPoint(), self::randomPoint()]), $db->LineString([self::randomPoint(), self::randomPoint()])]);
    $multiPolygon = $db->MultiPolygon([$db->Polygon([$db->LineString([$point1, $point2, $point3, $point4, $point1])]), $db->Polygon([$db->LineString([$point5, $point6, $point7, $point8, $point5])])]);

    $initialData = [
      'point' => self::randomPoint(),
      'geoNearPoint' => $geoNearPoint,
      'line' => $line,
      'polygon' => $polygon,
      'multiPoint' => $multiPoint,
      'multiLineString' => $multiLineString,
      'multiPolygon' => $multiPolygon
    ];

    self::$initialData = $initialData;

    // create
    $result = self::$collection->add($initialData);
    $this->assertEquals(isset($result['id']) && isset($result['requestId']), true);

    // read
    $res = self::$collection->where(['_id' => $result['id']])->get();
    $this->assertEquals(count($res['data']) > 0, true);

    $data = $res['data'][0];
    $this->assertEquals($data['point'] instanceof Point, true);
    $this->assertEquals($data['line'] instanceof LineString, true);
    $this->assertEquals($data['polygon'] instanceof Polygon, true);
    $this->assertEquals($data['multiPoint'] instanceof MultiPoint, true);
    $this->assertEquals($data['multiLineString'] instanceof MultiLineString, true);
    $this->assertEquals($data['multiPolygon'] instanceof MultiPolygon, true);

    $this->assertEquals($data['point'], $initialData['point']);
    $this->assertEquals($data['line'], $line);
    $this->assertEquals($data['polygon'], $polygon);
    $this->assertEquals($data['multiPoint'], $multiPoint);
    $this->assertEquals($data['multiLineString'], $multiLineString);
    $this->assertEquals($data['multiPolygon'], $multiPolygon);

    // update
    $res = self::$collection->doc($result['id'])->set($initialData);
    $this->assertEquals($res['updated'], 1);
    $this->assertEquals(isset($res['requestId']), true);

    //delete
    $deleteRes = self::$collection->where(['_id' => $result['id']])->remove();
    $this->assertEquals($deleteRes['deleted'], 1);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadPoint1()
  {
    self::$db->Point();
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadPoint2()
  {
    self::$db->Point([], []);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadLineString1()
  {
    self::$db->LineString([]);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadLineString2()
  {
    self::$db->LineString([123, []]);
  }


  /**
   * Undocumented function
   *
   * @expectedException Throwable 
   */
  public static function testBadPolygon1()
  {
    self::$db->Polygon(null);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadPolygon2()
  {
    self::$db->Polygon([]);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadPolygon3()
  {
    self::$db->Polygon([666, 789]);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadPolygon4()
  {
    // 回字外环
    $point1 = self::$point1;
    $point2 = self::$point2;
    $point3 = self::$point3;
    $point4 = self::$point4;

    // 回字内环
    $point5 = self::$point5;
    $point6 = self::$point6;
    $point7 = self::$point7;

    $point8 = self::$point8;
    $lineString = self::$db->LineString([$point1, $point2, $point3, $point4, $point8]);
    self::$db->Polygon([$lineString]);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadMultiPoint1()
  {
    self::$db->MultiPoint([]);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadMultiPoint2()
  {
    self::$db->MultiPoint([[], []]);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadMultiLineString1()
  {
    self::$db->MultiLineString([]);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadMultiLineString2()
  {
    self::$db->MultiLineString([123, null]);
  }


  /**
   * Undocumented function
   *
   * @expectedException Throwable
   */
  public static function testBadMultiPolygon1()
  {
    self::$db->MultiPolygon(123);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadMultiPolygon2()
  {
    self::$db->MultiPolygon([]);
  }

  /**
   * Undocumented function
   *
   * @expectedException TencentCloudBase\Utils\TcbException
   */
  public static function testBadMultiPolygon3()
  {
    self::$db->MultiPolygon([666, 666]);
  }


  public function testGeoNear()
  {
    // create
    $geoPoint = self::$db->Point(22, 33);
    $res = self::$collection->add(array_merge(self::$initialData, ['point' => $geoPoint]));
    $this->assertEquals(isset($res['id']) && isset($res['requestId']), true);

    // read
    $readRes = self::$collection->where(['point' => self::$_->geoNear(['geometry' => $geoPoint, 'maxDistance' => 1, 'minDistance' => 0])])->get();
    $this->assertEquals(count($readRes['data']) > 0, true); // 待预发验证
    $this->assertEquals($readRes['data'][0]['point'], $geoPoint); // 待预发验证

    // delete
    $deleteRes = self::$collection->where(['_id' => $res['id']])->remove();
    $this->assertEquals($deleteRes['deleted'], 1);
  }


  public function testGeoWithIn()
  {
    // 回字外环
    $point1 = self::$point1;
    $point2 = self::$point2;
    $point3 = self::$point3;
    $point4 = self::$point4;

    // create
    $geoPoint = self::$db->Point(0, 0);
    $res = self::$collection->add(array_merge(self::$initialData, ['point' => $geoPoint]));
    $this->assertEquals(isset($res['id']) && isset($res['requestId']), true);

    // read
    $readRes = self::$collection->where(['point' => self::$_->geoWithin(['geometry' => self::$db->Polygon([self::$db->LineString([$point1, $point2, $point3, $point4, $point1])])])])->get();
    $this->assertEquals(count($readRes['data']) > 0, true); // 待预发验证
    $this->assertEquals($readRes['data'][0]['point'], $geoPoint); // 待预发验证
    // delete

    $deleteRes = self::$collection->where(['point' => self::$_->geoWithin(['geometry' => self::$db->Polygon([self::$db->LineString([$point1, $point2, $point3, $point4, $point1])])])])->remove();
    $this->assertEquals($deleteRes['deleted'] >= 1, true); // 待预发验证
  }
}
