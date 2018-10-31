<?php

class Query {

    /**
     * Db 的引用
     *
     */
    protected $_db;

    /**
     * Collection name
     *
     */
    protected $_coll;

    
    function __construct($db, $coll) {
        $this->_db = $db;
        $this->_coll = $coll;
    }
}

?>