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
 * @method integer getOffset() 获取偏移量
 * @method void setOffset(integer $Offset) 设置偏移量
 * @method integer getLimit() 获取查询数量. 默认为 20
 * @method void setLimit(integer $Limit) 设置查询数量. 默认为 20
 * @method array getOrder() 获取排序规则. 可包含多个排序字段, 优先级依次降低（json数组）
 * @method void setOrder(array $Order) 设置排序规则. 可包含多个排序字段, 优先级依次降低（json数组）
 */

/**
 *DescribeDocument请求参数结构体
 */
class DescribeDocumentRequest extends AbstractModel
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
     * @var integer 偏移量
     */
    public $Offset;

    /**
     * @var integer 查询数量. 默认为 20
     */
    public $Limit;

    /**
     * @var array 排序规则. 可包含多个排序字段, 优先级依次降低（json数组）
     */
    public $Order;
    /**
     * @param CommParam $CommParam 公共入参
     * @param string $CollectionName 集合名称
     * @param string $Query 查询条件（json）
     * @param integer $Offset 偏移量
     * @param integer $Limit 查询数量. 默认为 20
     * @param array $Order 排序规则. 可包含多个排序字段, 优先级依次降低（json数组）
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

        if (array_key_exists("Offset",$param) and $param["Offset"] !== null) {
            $this->Offset = $param["Offset"];
        }

        if (array_key_exists("Limit",$param) and $param["Limit"] !== null) {
            $this->Limit = $param["Limit"];
        }

        if (array_key_exists("Order",$param) and $param["Order"] !== null) {
            $this->Order = $param["Order"];
        }
    }
}
