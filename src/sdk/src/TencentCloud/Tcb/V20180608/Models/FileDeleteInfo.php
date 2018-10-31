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
 * @method string getFileId() 获取文件唯一id
 * @method void setFileId(string $FileId) 设置文件唯一id
 * @method string getCode() 获取删除操作结果
 * @method void setCode(string $Code) 设置删除操作结果
 */

/**
 *DeleteFiles-文件删除信息
 */
class FileDeleteInfo extends AbstractModel
{
    /**
     * @var string 文件唯一id
     */
    public $FileId;

    /**
     * @var string 删除操作结果
     */
    public $Code;
    /**
     * @param string $FileId 文件唯一id
     * @param string $Code 删除操作结果
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

        if (array_key_exists("Code",$param) and $param["Code"] !== null) {
            $this->Code = $param["Code"];
        }
    }
}
