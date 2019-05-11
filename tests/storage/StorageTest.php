<?php
require_once "index.php";
// require_once "tests/autoload.php";

// require_once "tests/config/index.php";

use PHPUnit\Framework\TestCase;
use TencentCloudBase\TCB;

class StorageTest extends TestCase
{

  private static $tcb;

  public static function setUpBeforeClass()
  {
    global $TcbConfig;
    $TcbConfig = array("secretId" => "AKIDkOrrlYnf2ERxNeyna9Zowq4A4mNnl63p", "secretKey" => "juAIG5jCSXQAi9PUN3ax9Bj6HONDuQjq");
    self::$tcb = new Tcb($TcbConfig);
  }

  /** @test 批量获取下载链接 */
  public function testGetTempFileURL()
  {
    try {
      $storage = self::$tcb->getStorage();
      $result = $storage->getTempFileURL([
        "fileList" => [
          // ["fileID" => "cloud://tcbenv-mPIgjhnq.test-13db21/a|b.jpeg", "maxAge" => 100000] // local
          ["fileID" => "cloud://jimmytest-088bef.jimmytest-088bef-1251059088/a|b.jpeg", "maxAge" => 100000] // 预发
        ]
      ]);

      $fileList = $result["fileList"];
      $this->assertEquals(count($fileList), 1);
      $this->assertEquals(count($fileList[0]), 3);
      $this->assertEquals($fileList[0]["code"], "SUCCESS");
    } catch (Exception $e) {
      $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
      echo $code;
      echo "\r\n";
      echo $e->getMessage();
    }
  }

  /** @test 删除文件 */
  public function testDeleteFile()
  {
    try {
      $storage = self::$tcb->getStorage();
      $result = $storage->deleteFile([
        "fileList" => [
          // "cloud://tcbenv-mPIgjhnq.test-13db21/a|b.jpeg"
          "cloud://jimmytest-088bef.jimmytest-088bef-1251059088/a|b.jpeg"
        ]
      ]);

      $fileList = $result["fileList"];
      $this->assertEquals(count($fileList), 1);
      $this->assertEquals(count($fileList[0]), 2);
      $this->assertEquals($fileList[0]["code"], "SUCCESS");
    } catch (Exception $e) {
      $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
      echo $code;
      echo "\r\n";
      echo $e->getMessage();
    }
  }

  /** @test 下载文件 */
  public function testDownloadFile()
  {
    try {
      $storage = self::$tcb->getStorage();
      $result = $storage->downloadFile([
        "fileID" => "cloud://jimmytest-088bef.jimmytest-088bef-1251059088/a|b.jpeg",
        "tempFilePath" => "./tests/2.jpg"
      ]);
      $this->assertEquals(is_string($result["requestId"]), 1);
      file_exists('./tests/2.jpg');
    } catch (Exception $e) {
      $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
      echo $code;
      echo "\r\n";
      echo $e->getMessage();
    }
  }

  /** @test 上传文件 */
  public function testUploadFile()
  {
    try {
      $storage = self::$tcb->getStorage();

      //  打开文件
      $fileContent = fopen('./tests/1.jpg', 'r');
      print_r($fileContent);
      $cloudPath = 'a|b.jpeg';

      $fileResult = $storage->uploadFile(array('cloudPath' => $cloudPath, 'fileContent' => $fileContent));
      print_r($fileResult);
      $this->assertEquals($fileResult["code"], "SUCCESS");
      // file_exists('./tests/1.jpg');
    } catch (Exception $e) {
      $code = method_exists($e, 'getErrorCode') ? $e->getErrorCode() : $e->getCode();
      echo $code;
      echo "\r\n";
      echo $e->getMessage();
    }
  }
}
