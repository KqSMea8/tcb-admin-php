<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace TencentCloud\Tcb\V20180608\Models;
use TencentCloud\Common\AbstractModel;

/**
 * @method string getFileId() 获取文件id
 * @method void setFileId(string $FileId) 设置文件id
 * @method integer getTTL() 获取链接有效期，单位秒（private需要）
 * @method void setTTL(integer $TTL) 设置链接有效期，单位秒（private需要）
 */

/**
 *GetUploadFileUrl-批量获取文件下载链接
 */
class FileDownloadReqInfo extends AbstractModel
{
    /**
     * @var string 文件id
     */
    public $FileId;

    /**
     * @var integer 链接有效期，单位秒（private需要）
     */
    public $TTL;
    /**
     * @param string $FileId 文件id
     * @param integer $TTL 链接有效期，单位秒（private需要）
     */
    function __construct()
    {

    }
    /**
     * 内部实现，用户禁止调用
     */
    public function deserialize($param)
    {
        if ($param === null) {
            return;
        }
        if (array_key_exists("FileId",$param) and $param["FileId"] !== null) {
            $this->FileId = $param["FileId"];
        }

        if (array_key_exists("TTL",$param) and $param["TTL"] !== null) {
            $this->TTL = $param["TTL"];
        }
    }
}
