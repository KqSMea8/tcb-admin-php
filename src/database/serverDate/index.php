<?php

class ServerDate {
    
    public $offset;

    function __construct($options = ['offset' => 0]) {
        $this->offset = $options['offset'];
    }
}

?>