<?php
// require_once "tests/autoload.php";

use PHPUnit\Framework\TestCase;
use TencentCloudBase\Utils\TcbException;
use TencentCloudBase\Database\Util;
use TencentCloudBase\TCB;
use TencentCloudBase\Consts\Code;


class DocumentTest extends TestCase
{

  private static $tcb;

  private static $db;

  private static $databaseConfig;

  private static $_;

  private static $collName;

  private static $collection;

  private static $initialData;

  private static $docIDGenerated;

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
    self::$collName = "coll-2";
    self::$collection = self::$db->collection(self::$collName);
    self::$docIDGenerated = Util::generateDocId();
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


  // public function testCreateCollection()
  // {
  //   $_ = self::$_;
  //   $data = self::$db->createCollection(self::$collName);
  //   // $this->assertEquals($data['message'], "success");
  // }

  public function testDocId()
  {
    $document = self::$collection->doc(self::$docIDGenerated);
    $this->assertEquals($document->id, self::$docIDGenerated);
  }

  public function testSetDataInEmptyDoc()
  {
    try {
      self::$collection->doc()->set(['name' => 'jude']);
    } catch (TcbException $e) {
      $this->assertEquals($e->getErrorCode(), Code::INVALID_PARAM); // todo
    }

    // add one doc
    $addResult = self::$collection->add(['name' => 'jude']);
    $this->assertEquals(!empty($addResult['id'] && !empty($addResult['requestId'])), true);
  }

  public function testSetDataInExistDoc()
  {
    $document = self::$collection->limit(1)->get();
    $docId = $document['data'][0]['_id'];
    $data = self::$collection->doc($docId)->set(['data' => ['type' => 'set']]);
    $this->assertEquals($data['updated'], 1);

    $data = self::$collection->doc($docId)->set(['data' => ['arr' => [1, 2, 3], 'foo' => 123], 'array' => [0, 0, 0]]);
    $this->assertEquals($data['updated'], 1);

    $data = self::$collection->doc($docId)->update(['data' => ['arr' => self::$_->push([4, 5, 6]), 'foo' => self::$_->inc(1)], 'array' => self::$_->pop()]);
    $this->assertEquals($data['updated'], 1);

    // $document = self::$collection->doc($docId)->get();
  }

  public function testRemoveNotExistDoc()
  {
    $data = self::$collection->doc(self::$docIDGenerated)->remove();
    $this->assertEquals($data['deleted'], 0);
  }

  public function testRemoveDocExist()
  {
    $documents = self::$collection->get();
    $docId = $documents['data'][0]['_id'];
    $data = self::$collection->doc($docId)->remove();
    $this->assertEquals($data['deleted'], 1);
  }
}
