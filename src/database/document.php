<?php

require_once "src/database/util.php";
require_once "src/consts/code.php";
require_once "src/database/commands/update.php";

use Tcb\TcbException;

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
        $args["action"] = "database.addDocument";
        
        $data = Util::encodeDocumentDataForReq($data, false, false);
        var_dump($data);

        $args["params"] = [
            "collectionName" => $this->_coll,
            "data" => json_encode($data)
        ];
      
        if ($this->id) {
            $args["params"]["Id"] = $this->id;
        }
        
        return $this->_db->cloudApiRequest($args);
    }

    /**
     * 创建或添加数据
     * 
     * 如果文档ID不存在，则创建该文档并插入数据，根据返回数据的 upserted_id 判断
     * 添加数据的话，根据返回数据的 set 判断影响的行数
     *
     * @param [Array] $data
     * @return Array
     */
    public function set($data) {
        if (!isset($data) || !is_array($data)) {
            throw new TcbException(INVALID_PARAM, "参数必需是非空对象");
        }

        if (isset($data["_id"])) {
            throw new TcbException(INVALID_PARAM, "不能更新_id的值");
        }

        // 检查操作符
        $hasOperator = false;
        function checkMixed($arr) {
            if (is_array($arr)) {
                foreach ($arr as $val) {
                    if ($val instanceof UpdateCommand) {
                        $hasOperator = true;
                    }
                    elseif (is_array($val)) {
                        checkMixed($val);
                    }
                }
            }
        }
        checkMixed($data);

        // 不能包含操作符
        if (hasOperator) {  
            throw new TcbException(DATABASE_REQUEST_FAILED, "update operator complicit");
        }

        $args = [];
        $args["action"] = "database.updateDocument";
        $args["params"] = [
            "collectionName" => $this->_coll,
            "multi" => false,
            "merge" => false, // data不能带有操作符
            "upsert" => true,
            "data" => $data
        ];

        if (isset($this->id)) {
            $args["params"]["query"]["_id"] = $this->id;
        }

        $res = $this->_db->cloudApiRequest($args);

        if (isset($res["code"])) {
            return $res;
        }
        else {
            $documents = Util::formatResDocumentData($res["data"]["list"]);
            $result = [
                "updated" => $res["data"]["updated"],
                "upsertId" => $res["data"]["upsert_id"],
                "requestId" => $res["requestId"]
            ];

            return $result;
        }
    }

    /**
     * 更新数据
     *
     * @param [Array] $data - 文档数据
     * @return Array
     */
    public function update($data) {
        if (!isset($data) && !is_array($data)) {
            throw new TcbException(EMPTY_PARAM, "参数必需是非空对象");
        }

        if (isset($data["_id"])) {
            throw new TcbException(INVALID_PARAM, "不能更新 _id 的值");
        }

        $args = array();
        $args["action"] = "ModifyDocument";
    
        $query = [ "_id" => $this->id ];
        $merge = true; // 把所有更新数据转为带操作符的
        $args["params"] = [
            "collectionName" => $this->_coll,
            "data" => json_encode(Util::encodeDocumentDataForReq($data, $merge, true)),
            "query" => json_encode($query),
            "multi" => false,
            "merge" => $merge,
            "upsert" => false
        ];

        $res = $this->_db->cloudApiRequest($args);

        if (isset($res["code"])) {
            return $res;
        }
        else {
            $documents = Util::formatResDocumentData($res["data"]["list"]);
            $result = [
                "updated" => $res["data"]["updated"],
                "upsertId" => $res["data"]["upsert_id"],
                "requestId" => $res["requestId"]
            ];

            return $result;
        }
    }

    /**
     * 删除文档
     *
     * @return Array
     */
    public function remove() {
        $args = [];
        $args["action"] = "database.deleteDocument";

        $query = ["_id" => $this->id];

        $args["params"] = [
            "collectionName" => $this->_coll,
            "query" => json_encode($query),
            "multi" => false
        ];


        $res = $this->_db->cloudApiRequest($args);

        if (isset($res["code"])) {
            return $res;
        }
        else {
            $documents = Util::formatResDocumentData($res["data"]["list"]);
            $result = [
                "deleted" => $res["data"]["deleted"],
                "requestId" => $res["requestId"]
            ];

            return $result;
        }
    }

    public function get() {
        $args = [];
        $args["action"] = "database.queryDocument";

        $query = ["_id" => $this->id];

        $args["params"] = [
            "collectionName" => $this->_coll,
            "query" => json_encode($query),
            "multi" => false,
            "projection" => $this->projection
        ];

        $res = $this->_db->cloudApiRequest($args);

        if (isset($res["code"])) {
            return $res;
        }
        else {
            $documents = Util::formatResDocumentData($res["data"]["list"]);
            $result = [
                "data" => $documents,
                "requestId" => $res["requestId"]
            ];
    
            if (isset($res["TotalCount"])) {
                $result["total"] = $res["TotalCount"];
            }
            if (isset($res["Limit"])) {
                $result["limit"] = $res["Limit"];
            }
            if (isset($res["Offset"])) {
                $result["offset"] = $res["Offset"];
            }
            return $result;
        }
    }

    public function field($projection) {
        foreach ($projection as $k => $v) {
            if (isset($projection[$k])) {
              $projection[$k] = 1;
            }
            else {
              $projection[$k] = 0;
            }
        }
        return new DocumentReference($this->_db, $this->_coll, $this->id, $projection);
    }
}

?>