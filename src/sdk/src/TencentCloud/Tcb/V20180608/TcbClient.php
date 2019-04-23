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

namespace TencentCloud\Tcb\V20180608;

use TencentCloud\Common\AbstractClient;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Credential;
use TencentCloud\Tcb\V20180608\Models as Models;

/**
 * @method Models\CreateCollectionResponse CreateCollection(Models\CreateCollectionRequest $req) 创建集合
 * @method Models\CreateDocumentResponse CreateDocument(Models\CreateDocumentRequest $req) 添加文档数据
 * @method Models\DatabaseMigrateImportResponse DatabaseMigrateImport(Models\DatabaseMigrateImportRequest $req) 数据库导入数据
 * @method Models\DatabaseMigrateQueryInfoResponse DatabaseMigrateQueryInfo(Models\DatabaseMigrateQueryInfoRequest $req) 查询数据迁移进度
 * @method Models\DeleteDocumentResponse DeleteDocument(Models\DeleteDocumentRequest $req) 删除文档数据
 * @method Models\DeleteFilesResponse DeleteFiles(Models\DeleteFilesRequest $req) 批量删除文件
 * @method Models\DescribeDocumentResponse DescribeDocument(Models\DescribeDocumentRequest $req) 查询文档数据
 * @method Models\GetDownloadUrlsResponse GetDownloadUrls(Models\GetDownloadUrlsRequest $req) 批量获取文件访问链接
 * @method Models\GetUploadFileUrlResponse GetUploadFileUrl(Models\GetUploadFileUrlRequest $req) 获取上传链接
 * @method Models\InvokeAIResponse InvokeAI(Models\InvokeAIRequest $req) 执行AI服务
 * @method Models\InvokeFunctionResponse InvokeFunction(Models\InvokeFunctionRequest $req) 执行云函数
 * @method Models\ModifyDocumentResponse ModifyDocument(Models\ModifyDocumentRequest $req) 更新文档数据
 */

class TcbClient extends AbstractClient
{
    /**
     * @var string 产品默认域名
     */
    protected $endpoint = "tcb-admin.tencentcloudapi.com";

    /**
     * @var string api版本号
     */
    protected $version = "2018-06-08";

    /**
     * CvmClient constructor.
     * @param Credential $credential 认证类实例
     * @param string $region 地域
     * @param ClientProfile $profile client配置
     */
    function __construct($credential, $region, $profile = null)
    {
        parent::__construct($this->endpoint, $this->version, $credential, $region, $profile);
    }

    public function returnResponse($action, $response)
    {
        $respClass = "TencentCloud" . "\\" . ucfirst("tcb") . "\\" . "V20180608\\Models" . "\\" . ucfirst($action) . "Response";
        $obj = new $respClass();
        $obj->deserialize($response);
        return $obj;
    }
}
