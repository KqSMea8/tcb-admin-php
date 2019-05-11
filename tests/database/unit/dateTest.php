<?php
require_once "index.php";
// require_once "tests/config/index.php";

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Constraint\Exception;

class DateTest extends TestCase
{

  private static $tcb;

  private static $db;

  private static $databaseConfig;

  private static $_;

  private static $collName;

  private static $collection;

  private static $initialData;

  private static $offset;

  private static $timestamp;

  private static $time;

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
    $TcbConfig = array("secretId" => "AKIDkOrrlYnf2ERxNeyna9Zowq4A4mNnl63p", "secretKey" => "juAIG5jCSXQAi9PUN3ax9Bj6HONDuQjq");
    // $TcbConfig = array("secretId" => "AKIDY3Ws27uiEg0CC1QEg4GJCvWZUFrJhw66", "secretKey" => "2xiKmx1tdEhy76tVvJWggU7ZYP5cyCHO");
    self::$tcb = new Tcb($TcbConfig);
    self::$db = self::$tcb->getDatabase();
    self::$_ = self::$db->command;
    self::$collName = "coll-php-tes";
    self::$collection = self::$db->collection(self::$collName);
    self::$offset = 60 * 1000;

    // self::$timestamp = self::getMilTimeseconds();
    self::$timestamp = new DateTime();
    self::$time = self::$timestamp->getTimestamp();

    self::$initialData = [
      'name' => 'test',
      'date' => self::$timestamp,
      'serverDate1' => self::$db->serverDate(),
      'serverDate2' => self::$db->serverDate(['offset' => self::$offset]),
      'emptyArray' => [],
      'emptyObject' => [],
      'timestamp' => ['$timestamp' => self::$time],
      'foo' => ['bar' => self::$db->serverDate(['offset' => self::$offset])]
    ];
  }


  public function testDateCRUD()
  {
    $_ = self::$_;
    // $a = $_->gt(4);
    // $b = $_->or([['a' => $_->and($_->gt(10), $_->lte(20)), 'b' => $_->gt(10)], ['b' => $_->gt(20), 'a' => $_->lt(20)]]);

    // create
    $res = self::$collection->add(self::$initialData);
    $this->assertEquals(!empty($res['id'] && !empty($res['requestId'])), true);

    // read
    $id = $res['id'];
    $res = self::$collection->where(['_id' => $id])->get();
    $this->assertEquals($res['data'][0]['date'], self::$initialData['date']->getTimestamp() * 1000);
    $this->assertEquals(date("d", $res['data'][0]['serverDate1']), date("d", self::$initialData['date']->getTimestamp() * 1000));
    $this->assertEquals($res['data'][0]['serverDate1'] + self::$offset, $res['data'][0]['serverDate2']);
    $this->assertEquals($res['data'][0]['timestamp'], self::$time * 1000);
    $this->assertEquals($res['data'][0]['emptyArray'], []);
    $this->assertEquals($res['data'][0]['emptyObject'], []);

    $allTestRes = self::$collection->get();
    $res = self::$collection->where(['date' => $_->eq(self::$timestamp)])->get();
    $this->assertEquals($res['data'][0]['date'], self::$initialData['date']->getTimestamp() * 1000);

    $res = self::$collection->where(['date' => $_->lte(self::$timestamp)])->get();
    $this->assertEquals(count($res['data']) > 0, true);

    $res = self::$collection->where(['date' => $_->lte(self::$timestamp)->and($_->gte(self::$timestamp))])->get();
    $this->assertEquals(count($res['data']) > 0, true);

    // update
    $newDate = new DateTime();
    $newServerDate = self::$db->serverDate(['offset' => 1000 * 60 * 60]);
    $res = self::$collection->where(['date' => $_->lte(self::$timestamp)->and($_->gte(self::$timestamp))])->update(['date' => $newDate, 'serverDate2' => $newServerDate]);
    $this->assertEquals($res['updated'], 1);

    $res = self::$collection->where(['_id' => $id])->get();
    $this->assertEquals($res['data'][0]['date'], $newDate->getTimestamp() * 1000);
    $this->assertEquals($res['data'][0]['serverDate2'] - $res['data'][0]['serverDate1'] > 1000 * 60 * 60, true);


    // delete
    $res = self::$collection->doc($id)->remove();
    $this->assertEquals($res['deleted'], 1);
  }
}
