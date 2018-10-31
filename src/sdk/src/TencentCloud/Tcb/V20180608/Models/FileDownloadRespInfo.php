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
 * @method string getDownloadUrl() 获取访问链接
 * @method void setDownloadUrl(string $DownloadUrl) 设置访问链接
 * @method string getCode() 获取错误码，成功为SUCCESS
 * @method void setCode(string $Code) 设置错误码，成功为SUCCESS
 */

/**
 *GetDownloadUrls-文件下载链接resp对象
 */
class FileDownloadRespInfo extends AbstractModel
{
    /**
     * @var string 文件id
     */
    public $FileId;

    /**
     * @var string 访问链接
     */
    public $DownloadUrl;

    /**
     * @var string 错误码，成功为SUCCESS
     */
    public $Code;
    /**
     * @param string $FileId 文件id
     * @param string $DownloadUrl 访问链接
     * @param string $Code 错误码，成功为SUCCESS
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

        if (array_key_exists("DownloadUrl",$param) and $param["DownloadUrl"] !== null) {
            $this->DownloadUrl = $param["DownloadUrl"];
        }

        if (array_key_exists("Code",$param) and $param["Code"] !== null) {
            $this->Code = $param["Code"];
        }
    }
}
