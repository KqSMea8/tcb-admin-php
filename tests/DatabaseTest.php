<?php
require_once "index.php";
// require_once "tests/config/index.php";

use PHPUnit\Framework\TestCase;

// [
//     "a" => cmd->and([cmd->gte(10), cmd->lte(20)]),
//     "b" => cmd->gte(30)
// ]

// => 

// [
//     [
//         "$and" => [
//             [
//                 "a" => [
//                     "$gte" => 10,
//                 ]
//             ],
//             [
//                 "a" => [
//                     "$lte" => 20
//                 ]
//             ]
//         ]
//     ],
//     [
//         "b" => [
//             "$gte" => 30
//         ]
//     ]

// ]

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

  // /** @test ???? */
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
   * ???? */
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
   *  ???? */
  public function testQueryData($id)
  {
    try {
      self::$db->collection(self::$collName)->where(['_id' => $id])->get();
    } catch (Exception $e) { }
  }


  /** @test ????*/
  // public function testRemoveData()
  // {
  //   try {
  //     $db = self::$tcb->getDatabase();
  //     // $db->collection('testcollection')->doc("W9lw-3hEiJmgG5_5")->remove();
  //     $_ = $db->command;
  //     $db->collection('coll-1')->where($_->or([['properties' => ['memory' => $_->gt(8)]], ['properties' => ['cpu' => 3.2]]]))->remove();
  //   } catch (Exception $e) {
  //     $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
  //     echo $code;
  //     echo "\r\n";
  //     echo $e->getMessage();
  //   }
  // }


  // public function testCountData()
  // {
  //   $db = self::$tcb->getDatabase();
  //   $_ = $db->command;
  //   // $db->collection('coll-1')->where([
  //   //   "data" => $_->gt(50)->lt(100),
  //   //   "b" => $db->RegExp(['regexp' => 'miniprogram', 'options' => 'i']),
  //   //   "c" => $db->serverDate(['offset' => 60 * 60 * 1000])
  //   // ])->count();

  //   $db->collection('coll-1')->where([
  //     "data" => ['tags' => $_->push(['mini-program', 'cloud'])]
  //   ])->count();
  // }

  // public function testUpdateData()
  // {
  //   $db = self::$tcb->getDatabase();
  //   $_ = $db->command;
  //   // $db->collection('coll-1')->where([
  //   //   "data" => $_->gt(50)->lt(100),
  //   //   "b" => $db->RegExp(['regexp' => 'miniprogram', 'options' => 'i']),
  //   //   "c" => $db->serverDate(['offset' => 60 * 60 * 1000])
  //   // ])->count();

  //   $db->collection('coll-1')->where([
  //     'name' => $_->eq('hey')
  //   ])->update(['name' => 'Hey']);
  // }
}
