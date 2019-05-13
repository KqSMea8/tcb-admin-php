<?php
// require_once "tests/autoload.php";

use PHPUnit\Framework\TestCase;
use TencentCloudBase\TCB;

class OrderTest extends TestCase
{

  private static $tcb;

  private static $db;

  private static $databaseConfig;

  private static $_;

  private static $collName;

  private static $collection;

  private static $initialData;

  private static function is_orderArr($arr)
  {
    if (!is_array($arr)) {
      return false;
    }
    return array_keys($arr) === range(0, count($arr) - 1);
  }

  public static function setUpBeforeClass()
  {
    global $TcbConfig;
    $TcbConfig = array("secretId" => "AKIDkOrrlYnf2ERxNeyna9Zowq4A4mNnl63p", "secretKey" => "juAIG5jCSXQAi9PUN3ax9Bj6HONDuQjq");
    // $TcbConfig = array("secretId" => "AKIDY3Ws27uiEg0CC1QEg4GJCvWZUFrJhw66", "secretKey" => "2xiKmx1tdEhy76tVvJWggU7ZYP5cyCHO");
    self::$tcb = new Tcb($TcbConfig);
    self::$db = self::$tcb->getDatabase();
    self::$_ = self::$db->command;
    self::$collName = "coll-php-tes";
    self::$collection = self::$db->collection(self::$collName);
  }

  // public function testCreateCollection()
  // {
  //   self::$db->createCollection(self::$collName);
  // }

  public function testDocOrderBy()
  {
    // create 
    for ($i = 0; $i < 7; $i++) {
      $res = self::$collection->add([
        'category' => "类别B",
        'value' => random_int(0, 100)
      ]);
      $this->assertEquals(isset($res['id']) && isset($res['requestId']), true);
    }

    for ($i = 0; $i < 3; $i++) {
      $res = self::$collection->add([
        'category' => "类别C",
        'value' => random_int(0, 100)
      ]);
      $this->assertEquals(isset($res['id']) && isset($res['requestId']), true);
    }

    $res = self::$collection->add([
      'category' => "类别A",
      'value' => random_int(0, 100)
    ]);
    $this->assertEquals(isset($res['id']) && isset($res['requestId']), true);


    // read 

    $result = self::$collection->where([
      'category' => self::$db->RegExp(['regexp' => "^类别", 'options' => "i"])
    ])->orderBy("category", "asc")->get();
    $this->assertEquals(count($result['data']) >= 11, true);
    $this->assertEquals($result['data'][0]['category'] === '类别A', true);
    $this->assertEquals($result['data'][count($result['data']) - 1]['category'] === '类别C', true);

    // delete
    $deleteRes = self::$collection->where([
      'category' => self::$db->RegExp(['regexp' => "^类别"])
    ])->remove();
    $this->assertEquals($deleteRes['deleted'] >= 11, true);
  }
}
