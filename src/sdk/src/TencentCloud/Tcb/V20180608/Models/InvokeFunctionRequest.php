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
 * @method CommParam getCommParam() 获取公共参数
 * @method void setCommParam(CommParam $CommParam) 设置公共参数
 * @method string getFunctionName() 获取云函数名称
 * @method void setFunctionName(string $FunctionName) 设置云函数名称
 * @method string getFunctionParam() 获取云函数参数
 * @method void setFunctionParam(string $FunctionParam) 设置云函数参数
 */

/**
 *InvokeFunction请求参数结构体
 */
class InvokeFunctionRequest extends AbstractModel
{
    /**
     * @var CommParam 公共参数
     */
    public $CommParam;

    /**
     * @var string 云函数名称
     */
    public $FunctionName;

    /**
     * @var string 云函数参数
     */
    public $FunctionParam;
    /**
     * @param CommParam $CommParam 公共参数
     * @param string $FunctionName 云函数名称
     * @param string $FunctionParam 云函数参数
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

        if (array_key_exists("FunctionName",$param) and $param["FunctionName"] !== null) {
            $this->FunctionName = $param["FunctionName"];
        }

        if (array_key_exists("FunctionParam",$param) and $param["FunctionParam"] !== null) {
            $this->FunctionParam = $param["FunctionParam"];
        }
    }
}