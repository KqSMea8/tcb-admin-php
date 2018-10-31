<?php
require_once './src/functions/index.php';
require_once './src/storage/index.php';
require_once './src/database/index.php';

class TCB {

    public $config;

    public function __construct($options = [
        'secretId' => null,
        'secretKey' => null,
        'sessionToken' => null,
        'env' => null,
        'proxy' => null
    ]) {
        $this->config = [];

        // TODO: 检查 secret
        $this->config['secretId'] = $options['secretId'];
        $this->config['secretKey'] = $options['secretKey'];

        if (array_key_exists('sessionToken', $options)) {
            $this->config->sessionToken = $options['sessionToken'] || null;
        }

        if (array_key_exists('env', $options)) {
            $this->config['envName'] = $options['env'];
        }
    }

    public function getFunctions() {
        return new TcbFunctions($this->config);
    }

    public function getStorage() {
        return new TcbStorage($this->config);
    }

    public function getDatabase() {
        return new TcbDatabase($this->config);
    }

}


?>