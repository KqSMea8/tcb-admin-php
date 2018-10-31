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
 * @method string getMpAppId() 获取微信公众平台应用AppId
 * @method void setMpAppId(string $MpAppId) 设置微信公众平台应用AppId
 * @method string getEnvName() 获取tcb环境ID
 * @method void setEnvName(string $EnvName) 设置tcb环境ID
 * @method string getModule() 获取服务模块：第一期functions、storage和database可选
 * @method void setModule(string $Module) 设置服务模块：第一期functions、storage和database可选
 * @method string getOpenId() 获取微信公众平台用户openid
 * @method void setOpenId(string $OpenId) 设置微信公众平台用户openid
 */

/**
 *入参通用参数，所有接口都需要，与业务逻辑无关
 */
class CommParam extends AbstractModel
{
    /**
     * @var string 微信公众平台应用AppId
     */
    public $MpAppId;

    /**
     * @var string tcb环境ID
     */
    public $EnvName;

    /**
     * @var string 服务模块：第一期functions、storage和database可选
     */
    public $Module;

    /**
     * @var string 微信公众平台用户openid
     */
    public $OpenId;
    /**
     * @param string $MpAppId 微信公众平台应用AppId
     * @param string $EnvName tcb环境ID
     * @param string $Module 服务模块：第一期functions、storage和database可选
     * @param string $OpenId 微信公众平台用户openid
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
        if (array_key_exists("MpAppId",$param) and $param["MpAppId"] !== null) {
            $this->MpAppId = $param["MpAppId"];
        }

        if (array_key_exists("EnvName",$param) and $param["EnvName"] !== null) {
            $this->EnvName = $param["EnvName"];
        }

        if (array_key_exists("Module",$param) and $param["Module"] !== null) {
            $this->Module = $param["Module"];
        }

        if (array_key_exists("OpenId",$param) and $param["OpenId"] !== null) {
            $this->OpenId = $param["OpenId"];
        }
    }
}
