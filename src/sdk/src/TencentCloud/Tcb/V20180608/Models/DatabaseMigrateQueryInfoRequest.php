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
 * @method integer getJobId() 获取任务ID
 * @method void setJobId(integer $JobId) 设置任务ID
 */

/**
 *DatabaseMigrateQueryInfo请求参数结构体
 */
class DatabaseMigrateQueryInfoRequest extends AbstractModel
{
    /**
     * @var string 环境ID
     */
    public $EnvId;

    /**
     * @var integer 任务ID
     */
    public $JobId;
    /**
     * @param string $EnvId 环境ID
     * @param integer $JobId 任务ID
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

        if (array_key_exists("JobId",$param) and $param["JobId"] !== null) {
            $this->JobId = $param["JobId"];
        }
    }
}
