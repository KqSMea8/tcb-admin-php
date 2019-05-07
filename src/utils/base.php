<?php
// require_once 'src/sdk/TCloudAutoLoader.php';
require_once 'src/utils/autoload.php';
require_once 'src/utils/auth.php';

use Tcb\TcbAuth\Auth;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use function GuzzleHttp\json_decode;

class TcbBase
{
  protected $config;

  public function __construct($config)
  {
    $this->config = $config;
  }

  /**
   * ??ms???
   */
  public static function getMilTimeseconds()
  {
    return (int)(microtime(true) * 1000);
  }

  /**
   * ??eventId
   */
  public static function getEventId()
  {

    $timeStamp = self::getMilTimeseconds();
    // ??5?eventId
    $eventIdRand = rand(pow(10, (EVENTID_NUM - 1)), pow(10, EVENTID_NUM) - 1);
    $eventId = $timeStamp . '_' . $eventIdRand;
    return $eventId;
  }
  /**
   * ??seqId
   */
  public static function getSeqId()
  {
    $seqId = "";
    // ???????TCB_SEQID todo
    if (array_key_exists('TCB_SEQID', getenv())) {
      $seqId = getenv('TCB_SEQID');
    }

    $timeStamp = self::getMilTimeseconds();

    $seqId = $seqId !== "" ? $seqId . $timeStamp : (self::getEventId());
    return $seqId;
  }

  /**
   * multipart data transfer
   */

  public static function multiPartDataTran($rawData)
  {
    $multipartData = array();
    foreach ($rawData as $key => $value) {
      array_push($multipartData, array('name' => $key, 'contents' => $value));
    }
    return $multipartData;
  }

  public function cloudApiRequest($args = [
    'action' => null,
    'params' => [],
    'method' => null,
    'headers' => [],
    'timeout' => null,
    'config' => null
  ])
  {
    //
    $config = isEmpty($args['config']) ? $this->config : $args['config'];
    $protocol = "https";
    $pathname = "/admin";
    // $config['secretId'] = $this->config['secretId'];
    // $config['secretKey'] = $this->config['secretKey'];
    // $action = $args['action'];
    $params = $args['params'];

    $paramsAdd = array('timestamp' => self::getMilTimeseconds(), 'eventId' => self::getEventId());

    if (array_key_exists('envName', $this->config)) {
      // $config['envName'] = $this->config['envName'];
      $paramsAdd['envName'] = $this->config['envName'];
    }
    if (array_key_exists('isHttp', $this->config)) {
      $protocol = $this->config['isHttp'] ? 'http' : 'https';
    }
    //
    $method = $args['method'] ? $args['method'] : 'get';
    $params = array_merge($params, $paramsAdd);

    // ??params??null?key
    foreach ($params as $key => $value) {
      if ($value === null) {
        unset($params[$key]);
      }
    }

    // params.file ?wx.openApi??requestData ??????????
    $file = null;
    $requestData = null;
    if ($params['action'] === 'storage.uploadFile') { // ????unset??
      $file = $params['file'];
      unset($params['file']);
    }
    if ($params['action'] === 'wx.openApi') {
      $requestData = $params['requestData'];
      unset($params['requestData']);
    }

    // $params ?key????
    ksort($params);
    $authObj = array(
      'SecretId' => $this->config['secretId'],
      'SecretKey' => $this->config['secretKey'],
      'Method' => $method,
      'pathname' => $pathname,
      'Query' => $params,
      'Headers' => array_merge($args['headers'], array('user-agent' => 'tcb-admin-sdk/1.5.0-beta.1'))
    );

    $auth = new Auth($authObj);

    $authorization = $auth->getAuth();

    $params['authorization'] = $authorization;

    if ($file) {
      $params['file'] = $file;
    }
    if ($requestData) {
      $params['requestData'] = $requestData;
    }

    if (array_key_exists('sessionToken', $this->config) && $this->config['sessionToken']) {
      $params['sessionToken'] = $this->config['sessionToken'];
    }

    $params['sdk_version'] = '1.5.0-beta.1'; // todo 


    // ??url
    $url = $protocol . "://tcb-admin.tencentcloudapi.com";
    if (getenv('TENCENTCLOUD_RUNENV') === "SCF") {
      $protocol = "http";
      $url = $protocol . "://tcb-admin.tencentyun.com";
    }

    if ($params['action'] === "wx.api" || $params['action'] === "wx.openApi") {
      $url = $protocol . "://tcb-open.tencentcloudapi.com";
    }

    // ??????opts
    // $opts = array();
    // $defaultTimeout = 15000;
    // if (array_key_exists('serviceUrl', $this->config)) {
    //     $tempUrl = $this->config['serviceUrl'] ? $this->config['serviceUrl'] : $url;
    //     $opts['url'] = $tempUrl . '?' . 'seqId=' . self::getSeqId();
    // }
    // if (array_key_exists('proxy', $this->config)) {
    //     $opts['proxy'] = $this->config['proxy'];
    // }
    // if (array_key_exists('timeout', $this->config)) {
    //     $opts['timeout'] = $this->config['timeout'] ? $this->config['timeout'] : $defaultTimeout;
    // }
    // if (array_key_exists('timeout', $args)) {
    //     $opts['timeout'] = $args['timeout'] ? $args['timeout'] : $opts['timeout'];
    // }

    // $opts = array_merge($opts, array('method' => $method, 'headers' => $authObj['Headers']));

    // ?????????
    // storage.uploadFile  post(????json)  get
    $opts = array();
    if ($params['action'] === 'storage.uploadFile') {
      // multipart ??????
      $opts = array('headers' => $authObj['Headers'], 'multipart' => self::multiPartDataTran($params));
    } else {
      if ($method === 'post') {
        $opts = array('headers' => $authObj['Headers'], 'json' => $params);
      }
    }

    // guzzlehttp 构造请求
    $url = "http://localhost:8002";
    // $uri = "/admin";

    $http_client = new HttpClient(["base_uri" => $url]);
    try {
      // $resp = $http_client->post($pathname, $opts);
      $resp = $http_client->request('POST', $url . $pathname, $opts);
      $resultBody = $resp->getBody();
      $resultCode = $resp->getStatusCode();
      if ($resultCode === 200) {
        $bodyStr = $resultBody->getContents();
        $bodyResult = json_decode($bodyStr, true);
        return $bodyResult;
      }
    } catch (RequestException $e) {
      echo $e->getRequest();
    }
  }
}
