<?php

require_once "src/database/util.php";
require_once "src/consts/code.php";

use TencentCloud\Common\Exception\TencentCloudSDKException;

class DocumentReference {

    /**
     * 数据库引用
     *
     * @var [TcbDatabase]
     */
    private $_db;

    /**
     * 集合名称
     *
     * @var [String]
     */
    private $_coll;

    /**
     * 文档ID
     *
     * @var [String]
     */
    public $id;

    public $projection;

    function __construct($db, $coll, $docID, $projection = null) {
        $this->_db = $db;
        $this->_coll = $coll;
        $this->id = $docID;
        $this->projection = $projection;
    }

    /**
     * 创建一篇文档
     *
     * @param [Array] $data
     * @return Array
     */
    public function create($data) {
        $args = array();
        $args["action"] = "CreateDocument";
        
        $data = Util::encodeDocumentDataForReq($data, false, false);
        var_dump($data);

        $args["params"] = [
            "CollectionName" => $this->_coll,
            "Data" => json_encode($data)
        ];
      
        if ($this->id) {
            $args["params"]["Id"] = $this->id;
        }
        
        return $this->_db->cloudApiRequest($args);
    }

    public function set() {

    }

    /**
     * 更新数据
     *
     * @param [Array] $data - 文档数据
     * @return Array
     */
    public function update($data) {
        if (!isset($data) && !is_array($data)) {
            throw new TencentCloudSDKException(EMPTY_PARAM, "参数必需是非空对象");
        }

        if (isset($data["_id"])) {
            throw new TencentCloudSDKException(INVALID_PARAM, "不能更新 _id 的值");
        }

        $args = array();
        $args["action"] = "ModifyDocument";
    
        $query = [ "_id" => $this->id ];
        $merge = true; // 把所有更新数据转为带操作符的
        $args["params"] = [
            "CollectionName" => $this->_coll,
            "Data" => json_encode(Util::encodeDocumentDataForReq($data, $merge, true)),
            "Query" => json_encode($query),
            "Multi" => false,
            "Merge" => $merge,
            "Upsert" => false
        ];

        return $this->_db->cloudApiRequest($args);
    }

    /**
     * 删除文档
     *
     * @return Array
     */
    public function remove() {
        $args = array();
        $args["action"] = "DeleteDocument";

        $query = ["_id" => $this->id];

        $args["params"] = [
            "CollectionName" => $this->_coll,
            "Query" => json_encode($query),
            "Multi" => false
        ];


        return $this->_db->cloudApiRequest($args);
    }

    public function get() {

    }

    public function field() {
        
    }
}

?>