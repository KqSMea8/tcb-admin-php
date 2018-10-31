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
 * @method string getCollectionName() 获取集合名称
 * @method void setCollectionName(string $CollectionName) 设置集合名称
 * @method string getQuery() 获取查询条件（json）
 * @method void setQuery(string $Query) 设置查询条件（json）
 * @method boolean getMulti() 获取是否批量操作（默认false）
 * @method void setMulti(boolean $Multi) 设置是否批量操作（默认false）
 */

/**
 *DeleteDocument请求参数结构体
 */
class DeleteDocumentRequest extends AbstractModel
{
    /**
     * @var CommParam 公共入参
     */
    public $CommParam;

    /**
     * @var string 集合名称
     */
    public $CollectionName;

    /**
     * @var string 查询条件（json）
     */
    public $Query;

    /**
     * @var boolean 是否批量操作（默认false）
     */
    public $Multi;
    /**
     * @param CommParam $CommParam 公共入参
     * @param string $CollectionName 集合名称
     * @param string $Query 查询条件（json）
     * @param boolean $Multi 是否批量操作（默认false）
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

        if (array_key_exists("CollectionName",$param) and $param["CollectionName"] !== null) {
            $this->CollectionName = $param["CollectionName"];
        }

        if (array_key_exists("Query",$param) and $param["Query"] !== null) {
            $this->Query = $param["Query"];
        }

        if (array_key_exists("Multi",$param) and $param["Multi"] !== null) {
            $this->Multi = $param["Multi"];
        }
    }
}
