<?php
namespace Tcb\ServerDate;

class ServerDate
{

    public $offset;

    function __construct($options = ['offset' => 0])
    {
        $this->offset = $options['offset'];
    }

    public function parse()
    {
        return array('$date' => array('offset' => $this->offset));
    }
}
