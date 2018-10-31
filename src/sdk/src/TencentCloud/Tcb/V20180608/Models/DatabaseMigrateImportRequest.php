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
 * @method string getEnvId() 获取环境ID
 * @method void setEnvId(string $EnvId) 设置环境ID
 * @method string getCollectionName() 获取待导入的集合名称
 * @method void setCollectionName(string $CollectionName) 设置待导入的集合名称
 * @method string getFileType() 获取文件类型（json/csv）
 * @method void setFileType(string $FileType) 设置文件类型（json/csv）
 * @method string getFilePath() 获取上传的文件cos路径
 * @method void setFilePath(string $FilePath) 设置上传的文件cos路径
 * @method boolean getStopOnError() 获取遇到错误是是否停止导入
 * @method void setStopOnError(boolean $StopOnError) 设置遇到错误是是否停止导入
 * @method string getConflictMode() 获取冲突处理方式（insert/upsert）
 * @method void setConflictMode(string $ConflictMode) 设置冲突处理方式（insert/upsert）
 */

/**
 *DatabaseMigrateImport请求参数结构体
 */
class DatabaseMigrateImportRequest extends AbstractModel
{
    /**
     * @var string 环境ID
     */
    public $EnvId;

    /**
     * @var string 待导入的集合名称
     */
    public $CollectionName;

    /**
     * @var string 文件类型（json/csv）
     */
    public $FileType;

    /**
     * @var string 上传的文件cos路径
     */
    public $FilePath;

    /**
     * @var boolean 遇到错误是是否停止导入
     */
    public $StopOnError;

    /**
     * @var string 冲突处理方式（insert/upsert）
     */
    public $ConflictMode;
    /**
     * @param string $EnvId 环境ID
     * @param string $CollectionName 待导入的集合名称
     * @param string $FileType 文件类型（json/csv）
     * @param string $FilePath 上传的文件cos路径
     * @param boolean $StopOnError 遇到错误是是否停止导入
     * @param string $ConflictMode 冲突处理方式（insert/upsert）
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
        if (array_key_exists("EnvId",$param) and $param["EnvId"] !== null) {
            $this->EnvId = $param["EnvId"];
        }

        if (array_key_exists("CollectionName",$param) and $param["CollectionName"] !== null) {
            $this->CollectionName = $param["CollectionName"];
        }

        if (array_key_exists("FileType",$param) and $param["FileType"] !== null) {
            $this->FileType = $param["FileType"];
        }

        if (array_key_exists("FilePath",$param) and $param["FilePath"] !== null) {
            $this->FilePath = $param["FilePath"];
        }

        if (array_key_exists("StopOnError",$param) and $param["StopOnError"] !== null) {
            $this->StopOnError = $param["StopOnError"];
        }

        if (array_key_exists("ConflictMode",$param) and $param["ConflictMode"] !== null) {
            $this->ConflictMode = $param["ConflictMode"];
        }
    }
}
