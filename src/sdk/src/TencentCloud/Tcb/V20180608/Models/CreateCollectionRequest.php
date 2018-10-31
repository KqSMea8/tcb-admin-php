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
 * @method string getCollectionName() 获取集合名称
 * @method void setCollectionName(string $CollectionName) 设置集合名称
 * @method CommParam getCommParam() 获取公共参数
 * @method void setCommParam(CommParam $CommParam) 设置公共参数
 */

/**
 *CreateCollection请求参数结构体
 */
class CreateCollectionRequest extends AbstractModel
{
    /**
     * @var string 集合名称
     */
    public $CollectionName;

    /**
     * @var CommParam 公共参数
     */
    public $CommParam;
    /**
     * @param string $CollectionName 集合名称
     * @param CommParam $CommParam 公共参数
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
        if (array_key_exists("CollectionName",$param) and $param["CollectionName"] !== null) {
            $this->CollectionName = $param["CollectionName"];
        }

        if (array_key_exists("CommParam",$param) and $param["CommParam"] !== null) {
            $this->CommParam = new CommParam();
            $this->CommParam->deserialize($param["CommParam"]);
        }
    }
}
