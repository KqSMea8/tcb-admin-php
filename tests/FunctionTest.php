<?php
require_once "index.php";
// require_once "tests/config/index.php";
require __DIR__ . "/../vendor/autoload.php";

use PHPUnit\Framework\TestCase;

class FunctionTest extends TestCase {

    private static $tcb;

    public static function setUpBeforeClass() {
        global $TcbConfig;
        $TcbConfig = array("secretId"=>"AKIDkOrrlYnf2ERxNeyna9Zowq4A4mNnl63p","secretKey"=>"juAIG5jCSXQAi9PUN3ax9Bj6HONDuQjq","env"=>"tcbenv-mPIgjhnq");
        self::$tcb = new Tcb($TcbConfig);
    }

    /** @test */
    public function testCallFunction() {
        try {
            $functions = self::$tcb->getFunctions();
            echo '********************'.PHP_EOL;
            $result = $functions->callFunction([
                "name" => "test",
            ]);
            
            $this->assertEquals($result["result"]->result, 1);

        }
        catch (Exception $e) {
            $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
            echo '&&&&&&&&&&&&&&&&&&'.PHP_EOL;
            echo $code;
            echo "\r\n";
            echo $e->getMessage();
        }
    }
}

// FunctionTest::setUpBeforeClass();
// $functionTest = new FunctionTest();

// $functionTest->testCallFunction()
// FunctionTest::testCallFunction()

?>