<?php
require_once "index.php";
// require_once "tests/config/index.php";

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\Exception;

class CollectionTest extends TestCase
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

  /**
   * @test
   * name test
   */
  public function testCollectionName()
  {
    $this->assertEquals(self::$collection->name, self::$collName);
    self::$db->createCollection(self::$collName);
  }

  public function testOrderBy()
  {
    $field = "huming";
    $direction = "asc";
    $data = self::$collection->orderBy($field, $direction)->get();
    $this->assertEquals(self::is_orderArr($data['data']), true);
  }

  public function testLimit()
  {
    $limit = 1;
    $data = self::$collection->limit($limit)->get();
    $this->assertEquals(self::is_orderArr($data['data']) && count($data['data']) === $limit, true);
  }

  public function testOffset()
  {
    $offset = 2;
    $data = self::$collection->skip($offset)->get();
    $this->assertEquals(self::is_orderArr($data['data']), true);
  }

  public function testAddOneDocUpdateRemove()
  {
    $res = self::$collection->add(['name' => 'huming']);
    $this->assertEquals(!empty($res['id'] && !empty($res['requestId'])), true);

    $res = self::$collection->where(['name' => self::$_->eq('huming')])->update(['age' => 18]);
    $this->assertEquals($res['updated'] > 0, true);

    $res = self::$collection->where(['name' => self::$_->eq('huming')])->remove();
    $this->assertEquals($res['deleted'] > 0, true);
  }

  public function testField()
  {
    $res = self::$collection->field(['age' => 1])->get();
    $this->assertEquals(self::is_orderArr($res['data']), true);
  }

  public function testAddRemoveSkip()
  {
    $text = 'test for add and remove with skip';
    $i = 0;
    while ($i++ < 10) {
      self::$collection->add(['text' => $text]);
    }

    $res = self::$collection->where(['text' => $text])->get();
    $this->assertEquals(count($res['data']) > 0, true);

    self::$collection->where(['text' => $text])->orderBy('text', 'asc')->skip(3)->remove(); // ??
    $res = self::$collection->where(['text' => $text])->get();

    $this->assertEquals(count($res['data']) === 0, true);
  }
}
