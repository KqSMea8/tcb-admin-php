<?php
require_once "src/utils/base.php";
require_once "src/database/serverDate/index.php";
require_once "src/database/collection.php";
require_once "src/database/command.php";

require_once 'src/consts/code.php';

use TencentCloud\Common\Exception\TencentCloudSDKException;

class TcbDatabase extends TcbBase {

    protected $config;

    public $command;

    function __construct($config) {
        parent::__construct($config);
        $this->command = new Command();
    }

    public function serverDate($options = ["offset" => 0]) {
        $offset = $options["offset"];
        return new ServerDate(["offset" => $offset]);
    }

    public function collection($collName = null) {
        if (!isset($collName)) {
            throw new TencentCloudSDKException(EMPTY_PARAM, "Collection name is required");
        }
        
          return new CollectionReference($this, $collName);
    }

    /**
     * 创建集合
     *
     * @param [String] $collName
     * @return Array
     */
    public function createCollection($collName = null) {
        if (!isset($collName)) {
            throw new TencentCloudSDKException(EMPTY_PARAM, "Collection name is required");
        }

        $params = [
            "CollectionName" => $collName
        ];

        $args = array();
        $args["action"] = "CreateCollection";

        $args["params"] = array(
            "CollectionName" => $collName
        );

        $result = $this->cloudApiRequest($args);

        return [
            "requestId" => $result->RequestId
        ];
    }

}


?>