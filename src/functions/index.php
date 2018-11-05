<?php
require_once 'src/utils/base.php';
require_once 'src/consts/code.php';

use Tcb\TcbException;

class TcbFunctions extends TcbBase {

    protected $config;

    function __construct($config) {
        parent::__construct($config);
    }

    public function callFunction($options) {

        if (!array_key_exists('name', $options)) {
            throw new TcbException(FUNCTIONS_NAME_REQUIRED, '函数名不能为空');
        }

        $name = $options['name'];
        $data = array_key_exists('data', $options) ? $options['data'] : array();

        $args = array();
        $args['action'] = 'functions.invokeFunction';

        $args['params'] = array(
            'FunctionName' => $name,
            'FunctionParam' => json_encode($data)
        );

        try {
            $result = $this->cloudApiRequest($args);

            // 如果 code 和 message 存在，证明报错了
            if (property_exists($result, 'code')) {
                throw new TcbException($result->code, $result->message, $result->RequestId);
            }

            return [
                'requestId' => $result->RequestId,
                'result' => json_decode($result->Result),
            ];
        }
        catch (Exception $e) {
            throw new TcbException($e->getErrorCode(), $e->getMessage());
        }
    }
}

?>