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

    if (array_key_exists('sessionToken', $options)) {
      $this->config->sessionToken = $options['sessionToken'] ? $options['sessionToken'] : null;
    }

    if (array_key_exists('env', $options)) {
      $this->config['envName'] = $options['env'];
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
