<?php
require_once "index.php";
// require_once "tests/config/index.php";

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\Exception;

class CommandTest extends TestCase
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
      'name' => 'aaa',
      'array' => [1, 2, 3, [4, 5, 6], ['a' =>  1, 'b' =>  ['c' =>  'fjasklfljkas', 'd' =>  false]]],
      'data' => [
        'a' => 'a',
        'b' => 'b',
        'c' => 'c'
      ],
      'null' =>  null,
      'deepObject' =>  [
        'l-02-01' =>  [
          'l-03-01' => [
            'l-04-0 1' => [
              'leve l' =>  1,
              'nam e' =>  'l-01',
              'fla g' =>  '0'
            ]
          ]
        ]
      ]
    ];
  }


  public function testOperator()
  {
    $_ = self::$_;
    $a = $_->gt(4);
    $b = $_->or([['a' => $_->and($_->gt(10), $_->lte(20)), 'b' => $_->gt(10)], ['b' => $_->gt(20), 'a' => $_->lt(20)]]);
    $resultA = self::$collection->where(['a' => $a])->update(['c' => ['d' => $_->mul(3)]]);
    $resultB = self::$collection->where(['b' => $b])->update(['c' => ['d' => $_->mul(3)]]);
    $this->assertEquals(array_key_exists('updated', $resultA), true);
    $this->assertEquals(array_key_exists('updated', $resultB), true);
  }
}
