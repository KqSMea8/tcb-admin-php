<?php
require_once './src/functions/index.php';
require_once './src/storage/index.php';
require_once './src/database/index.php';
require_once './src/database/db.php';

class TCB
{
  public $config;

  public function __construct($options = [
    'secretId' => null,
    'secretKey' => null,
    'sessionToken' => null,
    'env' => null,
    'isHttp' => null,
    'proxy' => null,
    'timeout' => null,
    'serviceUrl' => null
  ])
  {
    $this->config = [];

    // TODO: 检查 secret
    $this->config['secretId'] = $options['secretId'];
    $this->config['secretKey'] = $options['secretKey'];

    if (!$this->config['secretKey'] || !$this->config['secretId']) {
      if (getenv('TENCENTCLOUD_RUNENV') === 'SCF') {
        throw new TcbException(INVALID_PARAM, "missing authoration key, redeploy the function");
      }
      throw new TcbException(INVALID_PARAM, "missing secretId or secretKey of tencent cloud");
    }

    if (array_key_exists('isHttp', $options)) {
      $this->config->isHttp = $options['isHttp']; // -> ???????
    }

    if (array_key_exists('env', $options)) {
      $this->config['envName'] = $options['env'];
    }

    if (array_key_exists('proxy', $options)) {
      $this->config['proxy'] = $options['proxy'];
    }

    if (array_key_exists('serviceUrl', $options)) {
      $this->config['serviceUrl'] = $options['serviceUrl'];
    }

    if (array_key_exists('timeout', $options)) {
      $this->config['timeout'] = $options['timeout'];
    }

    if (array_key_exists('sessionToken', $options)) {
      if (!empty($options['sessionToken'])) {
        $this->config->sessionToken = $options['sessionToken'];
      } else if ($this->config['secretId'] && $this->config['secretKey']) {
        $this->config->sessionToken = null;
      } else {
        $envSessionToken = getenv('TENCENTCLOUD_SESSIONTOKEN');
        $this->config->sessionToken = $envSessionToken ? $envSessionToken : null;
      }
    }
  }

  public function getFunctions()
  {
    return new TcbFunctions($this->config);
  }

  public function getStorage()
  {
    return new TcbStorage($this->config);
  }

  public function getDatabase($dbConfig = [])
  {
    return new Db(array_merge($this->config, $dbConfig));
  }
}
