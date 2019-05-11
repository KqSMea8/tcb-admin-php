<?php
require_once "tests/autoload.php";

use PHPUnit\Framework\TestCase;
use TencentCloudBase\Database\Validate;
use TencentCloudBase\Utils\TcbException;
use TencentCloudBase\TCB;

class ValidateTest extends TestCase
{

  private static $tcb;

  private static $db;

  private static $databaseConfig;

  private static $_;

  private static $collName;

  private static $collection;

  private static $initialData;

  private static $validateIns;

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
    self::$validateIns = new Validate();
  }

  public function testFieldOrderValid()
  {
    $this->assertEquals(self::$validateIns->isFieldOrder("desc"), true);
  }

  public function testFieldOrderInvalid()
  {
    try {
      self::$validateIns->isFieldOrder("desc1");
    } catch (TcbException $e) {
      $this->assertEquals($e->getErrorCode(), DirectionError);
    }
  }

  public function testOperatorValid()
  {
    $this->assertEquals(self::$validateIns->isOperator("<"), true);
  }

  public function testOperatorInvalid()
  {
    try {
      self::$validateIns->isOperator("<+");
    } catch (TcbException $e) {
      $this->assertEquals($e->getErrorCode(), OpStrError);
    }
  }

  public function testColNameValid()
  {
    $this->assertEquals(self::$validateIns->isCollName("coll-1_2"), true);
  }

  public function testColNameUse_Begin()
  {
    try {
      self::$validateIns->isCollName("_coll-1");
    } catch (TcbException $e) {
      $this->assertEquals($e->getErrorCode(), CollNameError);
    }
  }

  public function testColNameUseSpecialChar()
  {
    try {
      self::$validateIns->isCollName("coll-1_@#$");
    } catch (TcbException $e) {
      $this->assertEquals($e->getErrorCode(), CollNameError);
    }
  }

  public function testColNameEmpty()
  {
    try {
      self::$validateIns->isCollName("");
    } catch (TcbException $e) {
      $this->assertEquals($e->getErrorCode(), CollNameError);
    }
  }

  public function testColNameMoreThan32()
  {
    try {
      self::$validateIns->isCollName("abcdefgh12abcdefgh12abcdefgh12abcdefgh12");
    } catch (TcbException $e) {
      $this->assertEquals($e->getErrorCode(), CollNameError);
    }
  }

  public function testDocIdValid()
  {
    $docId = "abcdefABCDEF0123456789ab";
    $this->assertEquals(self::$validateIns->isDocID($docId), true);
  }

  public function testDocIdEmpty()
  {
    try {
      self::$validateIns->isDocID("");
    } catch (TcbException $e) {
      $this->assertEquals($e->getErrorCode(), DocIDError);
    }
  }

  public function testDocIdMoreThan24()
  {
    $docId = "abcdefABCDEF0123456789abcdef";
    try {
      self::$validateIns->isDocID($docId);
    } catch (TcbException $e) {
      $this->assertEquals($e->getErrorCode(), DocIDError);
    }
  }

  public function testDocIdUseSpecialChar()
  {
    $docId = "abcdefABCDEF0123456789@#";
    try {
      self::$validateIns->isDocID($docId);
    } catch (TcbException $e) {
      $this->assertEquals($e->getErrorCode(), DocIDError);
    }
  }
}
