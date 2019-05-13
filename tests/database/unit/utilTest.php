<?php
// require_once "tests/autoload.php";

use PHPUnit\Framework\TestCase;
// use Tcb\Util\Util;
use TencentCloudBase\Database\Util;
use TencentCloudBase\TCB;


class UtilTest extends TestCase
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
      'data' => [[
        '_id' => "5b2509efdc9c81268a7348d3",
        'a' => "a",
        'b' => 23,
        'c' => [
          'a' => "a",
          'b' => "b"
        ],
        'd' => [
          "1",
          "2",
          [
            '$date' => 1529234575
          ]
        ],
        'e' => true,
        'f' => null,
        'g' => [
          'coordinates' => [23, -78],
          'type' => "Point"
        ],
        'w' => [
          '$date' => 1529154030
        ]
      ]],
      'db_name' => "default",
      'env_key' => "tcbenv-ZkSRV2p8",
      'pager' => [
        'offset' => 0,
        'length' => 6,
        'total' => 1
      ],
      'uin' => 100003143464
    ];
  }

  public function testFormatDocData()
  {
    $data = Util::formatResDocumentData(self::$initialData['data']);
    $this->assertEquals($data[0]["a"], "a");
    $this->assertEquals($data[0]["f"], null);
    $this->assertEquals($data[0]["g"]->longitude, 23);
    $this->assertEquals($data[0]["g"]->latitude, -78);
  }
}
