<?php
namespace Tcb\TcbException;

use TencentCloud\Common\Exception\TencentCloudSDKException;

class TcbException extends TencentCloudSDKException
{

    public function __construct($code = "", $message = "",  $requestId = "")
    {
        parent::__construct($code = "", $message = "",  $requestId = "");
    }
}

?>