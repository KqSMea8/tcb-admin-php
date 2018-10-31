<?php
require_once "index.php";
require_once "tests/config/index.php";

use PHPUnit\Framework\TestCase;

class FunctionTest extends TestCase {

    private static $tcb;

    public static function setUpBeforeClass() {
        global $TcbConfig;
        self::$tcb = new Tcb($TcbConfig);
    }

    /** @test */
    public function testCallFunction() {
        try {
            $functions = self::$tcb->getFunctions();
            $result = $functions->callFunction([
                "name" => "test",
            ]);
            
            $this->assertEquals($result["result"]->result, 1);

        }
        catch (Exception $e) {
            $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
            echo $code;
            echo "\r\n";
            echo $e->getMessage();
        }
    }
}

?>