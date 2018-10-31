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
 * @method string getData() 获取更新的文档数据（JSON）
 * @method void setData(string $Data) 设置更新的文档数据（JSON）
 * @method string getQuery() 获取查询条件（JSON）
 * @method void setQuery(string $Query) 设置查询条件（JSON）
 * @method boolean getMulti() 获取是否批量操作. 默认为 false, 即只更新符合规则的第一条数据
 * @method void setMulti(boolean $Multi) 设置是否批量操作. 默认为 false, 即只更新符合规则的第一条数据
 * @method boolean getMerge() 获取是否合并更新. 默认为 false, 即替换整条数据. 否则只会替换 data 传入的字段
 * @method void setMerge(boolean $Merge) 设置是否合并更新. 默认为 false, 即替换整条数据. 否则只会替换 data 传入的字段
 * @method boolean getUpsert() 获取当数据不存在的时候是否创建数据. 默认为 false, 只在 multi 为 false 时生效
 * @method void setUpsert(boolean $Upsert) 设置当数据不存在的时候是否创建数据. 默认为 false, 只在 multi 为 false 时生效
 */

/**
 *ModifyDocument请求参数结构体
 */
class ModifyDocumentRequest extends AbstractModel
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
     * @var string 更新的文档数据（JSON）
     */
    public $Data;

    /**
     * @var string 查询条件（JSON）
     */
    public $Query;

    /**
     * @var boolean 是否批量操作. 默认为 false, 即只更新符合规则的第一条数据
     */
    public $Multi;

    /**
     * @var boolean 是否合并更新. 默认为 false, 即替换整条数据. 否则只会替换 data 传入的字段
     */
    public $Merge;

    /**
     * @var boolean 当数据不存在的时候是否创建数据. 默认为 false, 只在 multi 为 false 时生效
     */
    public $Upsert;
    /**
     * @param CommParam $CommParam 公共入参
     * @param string $CollectionName 集合名称
     * @param string $Data 更新的文档数据（JSON）
     * @param string $Query 查询条件（JSON）
     * @param boolean $Multi 是否批量操作. 默认为 false, 即只更新符合规则的第一条数据
     * @param boolean $Merge 是否合并更新. 默认为 false, 即替换整条数据. 否则只会替换 data 传入的字段
     * @param boolean $Upsert 当数据不存在的时候是否创建数据. 默认为 false, 只在 multi 为 false 时生效
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

        if (array_key_exists("Data",$param) and $param["Data"] !== null) {
            $this->Data = $param["Data"];
        }

        if (array_key_exists("Query",$param) and $param["Query"] !== null) {
            $this->Query = $param["Query"];
        }

        if (array_key_exists("Multi",$param) and $param["Multi"] !== null) {
            $this->Multi = $param["Multi"];
        }

        if (array_key_exists("Merge",$param) and $param["Merge"] !== null) {
            $this->Merge = $param["Merge"];
        }

        if (array_key_exists("Upsert",$param) and $param["Upsert"] !== null) {
            $this->Upsert = $param["Upsert"];
        }
    }
}
