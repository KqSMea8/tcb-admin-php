<?php
require_once "tests/autoload.php";

use TencentCloudBase\TCB;
use PHPUnit\Framework\TestCase;

class RegexQueryTest extends TestCase
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
    self::$initialData = [
      'name' => "AbCdEfxxxxxxxxxxxxxx1234结尾",
      'array' => [1, 2, 3, [4, 5, 6], [
        'a' => 1,
        'b' => [
          'c' => "fjasklfljkas",
          'd' => false
        ]
      ]],
      'deepObject' => [
        "l-02-01" => [
          "l-03-01" => [
            "l-04-01" => [
              "level" => 1,
              "name" => "l-01",
              "flag" => "0"
            ]
          ]
        ]
      ]
    ];
  }

  // public function testCreateCollection()
  // {
  //   self::$db->createCollection(self::$collName);
  // }

  public function testDocOrderBy()
  {
    // create 
    $res = self::$collection->add(self::$initialData);
    $this->assertEquals(isset($res['id']) && isset($res['requestId']), true);

    // read 
    $result = self::$collection->where(['name' => self::$db->RegExp(['regexp' => "^abcdef.*\\d+结尾$", "options" => "i"])])->get();
    $this->assertEquals(count($result['data']) > 0, true);

    // update
    $result = self::$collection->where(
      [
        'name' => self::$_->or(
          self::$db->RegExp(['regexp' => "^abcdef.*\\d+结尾$", "options" => "i"]),
          self::$db->RegExp(['regexp' => "^fffffff$", "options" => "i"])
        )
      ]
    )->update(['name' => 'ABCDEFxxxx5678结尾']);
    $this->assertEquals($result['updated'] > 0, true);


    // delete
    $deleteRes = self::$collection->where([
      'name' => self::$db->RegExp(['regexp' => "^abcdef.*\\d+结尾$", "options" => "i"]),
    ])->remove();

    $this->assertEquals($deleteRes['deleted'] > 0, true);
  }
}
