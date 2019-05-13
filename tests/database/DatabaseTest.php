<?php
// require_once "tests/autoload.php";

use PHPUnit\Framework\TestCase;
use TencentCloudBase\TCB;

class DatabaseTest extends TestCase
{

  private static $tcb;

  private static $db;

  private static $databaseConfig;

  private static $_;

  private static $collName;

  private static $collection;

  private static $initialData;

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
            'l-04-01' => [
              'level' =>  1,
              'name' =>  'l-01',
              'flag' =>  '0'
            ]
          ]
        ]
      ]
    ];
  }

  // /** @test 创建集合 */
  // public function testCreateCollection()
  // {
  //   try {
  //     // $db = self::$tcb->getDatabase();
  //     $result = self::$db->createCollection(self::$collName);
  //     var_dump($result);
  //     $this->assertEquals($result['message'], 'success');
  //   } catch (Exception $e) {
  //     $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
  //     echo $code;
  //     echo "\r\n";
  //     echo $e->getMessage();
  //   }
  // }

  /** @test 
   * 
   * 新增文档 */
  public function testAddData()
  {
    try {
      $result = self::$db->collection(self::$collName)->add(self::$initialData);
      $this->assertEquals(!empty($result['id']), 1);
      $this->assertEquals(!empty($result['requestId']), 1);
    } catch (Exception $e) {
      $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
      echo $code;
      echo "\r\n";
      echo $e->getMessage();
    }
    return $result['id'];
  }

  /** @test
   *  @depends testAddData
   *  查询文档 */
  public function testQueryData($id)
  {
    try {
      $result = self::$db->collection(self::$collName)->where(['_id' => $id])->get();
      $this->assertEquals($result['data'][0]['name'], self::$initialData['name']);
      $this->assertEquals($result['data'][0]['array'], self::$initialData['array']);
      $this->assertEquals($result['data'][0]['deepObject'], self::$initialData['deepObject']);

      // 搜索某个字段为 null 时，应该复合下面条件的都应该返回：
      // 1. 这个字段严格等于 null
      // 2. 这个字段不存在
      // docs: https://docs.mongodb.com/manual/tutorial/query-for-null-fields/

      $result1 = self::$db->collection(self::$collName)->where(['fakeFields' => self::$_->or(self::$_->eq(null))])->get();
      $this->assertEquals(count($result1['data']) > 0, true);

      //
      $doc = self::$db->collection(self::$collName)->doc($id)->get();
      $this->assertEquals($doc['data'][0]['name'], self::$initialData['name']);
      $this->assertEquals($doc['data'][0]['array'], self::$initialData['array']);
      $this->assertEquals($doc['data'][0]['deepObject'], self::$initialData['deepObject']);
    } catch (Exception $e) { }
  }

  /** @test 
   *  @depends testAddData
   *  更新文档 */
  public function testUpdateData($id)
  {
    $result = self::$db->collection(self::$collName)->where(['_id' => $id])->update(['name' => 'bbb', 'array' => [['a' => 1, 'b' => 2, 'c' => 3]]]);
    $this->assertEquals($result['updated'] > 0, true);

    $result = self::$db->collection(self::$collName)->where(['_id' => $id])->update(['data' => ['a' => null, 'b' => null, 'c' => null]]);
    $this->assertEquals($result['updated'] > 0, true);

    $result = self::$db->collection(self::$collName)->where(['_id' => $id])->get();
    $this->assertEquals(!empty($result['data'][0]), true);
    $this->assertEquals($result['data'][0]['data'], ['a' => null, 'b' => null, 'c' => null]);

    // 数组变为对象，mongo会报错
    $result = self::$db->collection(self::$collName)->where(['_id' => $id])->update(['array' => ['foo' => 'bar']]);
    $this->assertEquals($result['code'], 'DATABASE_REQUEST_FAILED');

    $result = self::$db->collection(self::$collName)->where(['_id' => $id])->get();
    $this->assertEquals($result['data'][0]['array'], [['a' => 1, 'b' => 2, 'c' => 3]]);
  }


  /** @test 删除文档
   * @depends testAddData
   */
  public function testRemoveData($id)
  {
    try {
      $result = self::$db->collection(self::$collName)->doc($id)->remove();
      $this->assertEquals($result['deleted'], 1);
    } catch (Exception $e) {
      $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
      echo $code;
      echo "\r\n";
      echo $e->getMessage();
    }
  }

  /**
   * @test
   * 查询文档（单独测试）
   */
  public function testSecondQuery()
  {
    $_ = self::$_;
    $result = self::$db->collection(self::$collName)->add(['a' => 1, 'b' => 100]);
    $result1 = self::$db->collection(self::$collName)->add(['a' => 10, 'b' => 1]);
    $this->assertEquals(!empty($result['id']), true);
    $this->assertEquals(!empty($result1['id']), true);
    $query = $_->or([['b' => $_->and($_->gte(1), $_->lte(10))], ['b' => $_->and($_->gt(99), $_->lte(101))]]);
    $result2 = self::$db->collection(self::$collName)->where($query)->get();
    $this->assertEquals(count($result2['data']) >= 2, true);

    // delete
    $deleteRes = self::$db->collection(self::$collName)->where($query)->remove();
    $this->assertEquals($deleteRes['deleted'], 2);
  }
}
