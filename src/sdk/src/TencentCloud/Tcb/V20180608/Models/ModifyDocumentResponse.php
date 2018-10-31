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
 * @method integer getUpdated() 获取成功更新的文档数量
 * @method void setUpdated(integer $Updated) 设置成功更新的文档数量
 * @method string getUpsertedId() 获取upsert 为 true 时, 为添加的文档 _id，否则为空
 * @method void setUpsertedId(string $UpsertedId) 设置upsert 为 true 时, 为添加的文档 _id，否则为空
 * @method string getRequestId() 获取唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 * @method void setRequestId(string $RequestId) 设置唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
 */

/**
 *ModifyDocument返回参数结构体
 */
class ModifyDocumentResponse extends AbstractModel
{
    /**
     * @var integer 成功更新的文档数量
     */
    public $Updated;

    /**
     * @var string upsert 为 true 时, 为添加的文档 _id，否则为空
     */
    public $UpsertedId;

    /**
     * @var string 唯一请求 ID，每次请求都会返回。定位问题时需要提供该次请求的 RequestId。
     */
    public $RequestId;
    /**
     * @param integer $Updated 成功更新的文档数量
     * @param string $UpsertedId upsert 为 true 时, 为添加的文档 _id，否则为空
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
        if (array_key_exists("Updated",$param) and $param["Updated"] !== null) {
            $this->Updated = $param["Updated"];
        }

        if (array_key_exists("UpsertedId",$param) and $param["UpsertedId"] !== null) {
            $this->UpsertedId = $param["UpsertedId"];
        }

        if (array_key_exists("RequestId",$param) and $param["RequestId"] !== null) {
            $this->RequestId = $param["RequestId"];
        }
    }
}
