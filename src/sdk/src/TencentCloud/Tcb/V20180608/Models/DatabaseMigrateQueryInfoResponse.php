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
 * @method string getStatus() 获取任务状态[waiting/reading/writing/migrating/success/fail]
 * @method void setStatus(string $Status) 设置任务状态[waiting/reading/writing/migrating/success/fail]
 * @method integer getRecordSuccess() 获取导入成功的数据条数
 * @method void setRecordSuccess(integer $RecordSuccess) 设置导入成功的数据条数
 * @method integer getRecordFail() 获取导入失败的数据条数
 * @method void setRecordFail(integer $RecordFail) 设置导入失败的数据条数
 * @method string getErrorMsg() 获取导入失败的原因
 * @method void setErrorMsg(string $ErrorMsg) 设置导入失败的原因
 * @method string getRequestId() 获取唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 * @method void setRequestId(string $RequestId) 设置唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 */

/**
 *DatabaseMigrateQueryInfo返回参数结构体
 */
class DatabaseMigrateQueryInfoResponse extends AbstractModel
{
    /**
     * @var string 任务状态[waiting/reading/writing/migrating/success/fail]
     */
    public $Status;

    /**
     * @var integer 导入成功的数据条数
     */
    public $RecordSuccess;

    /**
     * @var integer 导入失败的数据条数
     */
    public $RecordFail;

    /**
     * @var string 导入失败的原因
     */
    public $ErrorMsg;

    /**
     * @var string 唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
     */
    public $RequestId;
    /**
     * @param string $Status 任务状态[waiting/reading/writing/migrating/success/fail]
     * @param integer $RecordSuccess 导入成功的数据条数
     * @param integer $RecordFail 导入失败的数据条数
     * @param string $ErrorMsg 导入失败的原因
     * @param string $RequestId 唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
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
        if (array_key_exists("Status",$param) and $param["Status"] !== null) {
            $this->Status = $param["Status"];
        }

        if (array_key_exists("RecordSuccess",$param) and $param["RecordSuccess"] !== null) {
            $this->RecordSuccess = $param["RecordSuccess"];
        }

        if (array_key_exists("RecordFail",$param) and $param["RecordFail"] !== null) {
            $this->RecordFail = $param["RecordFail"];
        }

        if (array_key_exists("ErrorMsg",$param) and $param["ErrorMsg"] !== null) {
            $this->ErrorMsg = $param["ErrorMsg"];
        }

        if (array_key_exists("RequestId",$param) and $param["RequestId"] !== null) {
            $this->RequestId = $param["RequestId"];
        }
    }
}
