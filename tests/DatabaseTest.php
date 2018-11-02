<?php
require_once "index.php";
require_once "tests/config/index.php";

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

class DatabaseTest extends TestCase {

    private static $tcb;

    public static function setUpBeforeClass() {
        global $TcbConfig;
        self::$tcb = new Tcb($TcbConfig);
    }

    /** @test */
    public function testCreateCollection() {
        try {
            $db = self::$tcb->getDatabase();
            $result = $db->createCollection("testcollection");
        
            var_dump($result);
        }
        catch (Exception $e) {
            $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
            echo $code;
            echo "\r\n";
            echo $e->getMessage();
        } 
    }

    /** @test */
    public function testAddData() {
        try {
            $db = self::$tcb->getDatabase();
            $db->collection('testcollection')->add([
                "name"=> "heyli",
                "sex" => "male",
                "age" => 27,
                "location" => new Point(90, 30),
                "loginTime" => new ServerDate(["offset" => 120000]), // 毫秒
                "regTime" => new DateTime('2018-10-01')
            ]);
        }
        catch (Exception $e) {
            $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
            echo $code;
            echo "\r\n";
            echo $e->getMessage();
        } 
    }

    /** @test */
    public function testRemoveData() {
        try {
            $db = self::$tcb->getDatabase();
            $db->collection('testcollection')->doc("W9lw-3hEiJmgG5_5")->remove();
        }
        catch (Exception $e) {
            $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
            echo $code;
            echo "\r\n";
            echo $e->getMessage();
        } 
    }

    /** @test */
    public function testQueryData() {
        try {
            $db = self::$tcb->getDatabase();
            $db->collection('testcollection').where()
        }
        catch (Exception $e) {

        }
    }
}

?>