<?php
require_once "index.php";
require_once "tests/config/index.php";

use PHPUnit\Framework\TestCase;

class StorageTest extends TestCase {

    private static $tcb;

    public static function setUpBeforeClass() {
        global $TcbConfig;
        self::$tcb = new Tcb($TcbConfig);
    }

    /** @test */
    public function testGetTempFileURL() {
        try {
            $storage = self::$tcb->getStorage();
            $result = $storage->getTempFileURL([
                "fileList" => [
                    ["fileID" => "cloud://test-e48fe1.7465-test-e48fe1/1.jpg", "maxAge" => 100000]
                ]
            ]);

            $fileList = $result["fileList"];
            $this->assertEquals(count($fileList), 1);
            $this->assertEquals(count($fileList[0]), 3);
            $this->assertEquals($fileList[0]["code"], "SUCCESS");
        }
        catch (Exception $e) {
            $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
            echo $code;
            echo "\r\n";
            echo $e->getMessage();
        }
    }

    /** @test */
    public function testDeleteFile() {
        try {
            $storage = self::$tcb->getStorage();
            $result = $storage->deleteFile([
                "fileList" => [
                    "cloud://test-e48fe1.7465-test-e48fe1/2.jpg"
                ]
            ]);

            $fileList = $result["fileList"];
            $this->assertEquals(count($fileList), 1);
            $this->assertEquals(count($fileList[0]), 2);
            $this->assertEquals($fileList[0]["code"], "SUCCESS");
        }
        catch (Exception $e) {
            $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
            echo $code;
            echo "\r\n";
            echo $e->getMessage();
        }
    }

    /** @test */
    public function testDownloadFile() {
        try {
            $storage = self::$tcb->getStorage();
            $result = $storage->downloadFile([
                "fileID" => "cloud://test-e48fe1.7465-test-e48fe1/1.jpg",
                "tempFilePath" => "./tests/1.jpg"
            ]);
            $this->assertEquals(is_string($result["requestId"]), 1);
            file_exists('./tests/1.jpg');
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