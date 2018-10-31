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
 * @method CommParam getCommParam() 获取公共入参
 * @method void setCommParam(CommParam $CommParam) 设置公共入参
 * @method array getFileIds() 获取文件唯一id列表
 * @method void setFileIds(array $FileIds) 设置文件唯一id列表
 */

/**
 *DeleteFiles请求参数结构体
 */
class DeleteFilesRequest extends AbstractModel
{
    /**
     * @var CommParam 公共入参
     */
    public $CommParam;

    /**
     * @var array 文件唯一id列表
     */
    public $FileIds;
    /**
     * @param CommParam $CommParam 公共入参
     * @param array $FileIds 文件唯一id列表
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
        if (array_key_exists("CommParam",$param) and $param["CommParam"] !== null) {
            $this->CommParam = new CommParam();
            $this->CommParam->deserialize($param["CommParam"]);
        }

        if (array_key_exists("FileIds",$param) and $param["FileIds"] !== null) {
            $this->FileIds = $param["FileIds"];
        }
    }
}
