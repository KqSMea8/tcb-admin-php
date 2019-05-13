<?php
// require_once "tests/autoload.php";

// require __DIR__ . "/../../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use TencentCloudBase\TCB;

class FunctionTest extends TestCase
{

  private static $tcb;

  public static function setUpBeforeClass()
  {
    global $TcbConfig;
    $TcbConfig = array("secretId" => "AKIDkOrrlYnf2ERxNeyna9Zowq4A4mNnl63p", "secretKey" => "juAIG5jCSXQAi9PUN3ax9Bj6HONDuQjq");
    self::$tcb = new Tcb($TcbConfig);
  }

  /** @test */
  public function testCallFunction()
  {
    try {
      $functions = self::$tcb->getFunctions();
      $result = $functions->callFunction([
        "name" => "test",
        "data" => array('a' => 1)
      ]);

      $this->assertEquals($result["result"]->a, 1);
    } catch (Exception $e) {
      $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
      echo $code;
      echo "\r\n";
      echo $e->getMessage();
    }
  }
}
