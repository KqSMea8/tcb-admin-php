<?php
/*
 * Copyright (c) 2017-2018 THL A29 Limited, a Tencent company. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */

namespace TencentCloud\Common;

use \ReflectionClass;

use TencentCloud\Common\Http\HttpConnection;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Exception\TencentCloudSDKException;


/**
 * 抽象api类，禁止client引用
 * @package TencentCloud\Common
 */
abstract class AbstractClient
{
    /**
     * @var string SDK版本
     */
    public static $SDK_VERSION = "SDK_PHP_3.0.28";

    /**
     * @var integer http响应码200
     */
    public static $HTTP_RSP_OK = 200;


    /**
     * @var Credential 认证类实例，保存认证相关字段
     */
    private $credential;

    /**
     * @var ClientProfile 会话配置信息类
     */
    private $profile;

    /**
     * @var string 产品地域
     */
    private $region;

    /**
     * @var string 请求路径
     */
    private $path;

    /**
     * @var string sdk版本号
     */
    private $sdkVersion;

    /**
     * @var string api版本号
     */
    private $apiVersion;

    /**
     * 基础client类
     * @param string $endpoint 请求域名
     * @param string $version api版本
     * @param Credential $credential 认证信息实例
     * @param string $region 产品地域
     * @param ClientProfile $profile
     */
    function __construct($endpoint, $version, $credential, $region, $profile = null)
    {
        $this->path = "/admin";

        $this->credential = $credential;
        $this->region = $region;
        if ($profile) {
            $this->profile = $profile;
        } else {
            $this->profile = new ClientProfile();
        }
        if ($this->profile->getHttpProfile()->getEndpoint() === null) {
            $this->profile->getHttpProfile()->setEndpoint($endpoint);
        }
        $this->sdkVersion = AbstractClient::$SDK_VERSION;
        $this->apiVersion = $version;
    }

    /**
     * 设置产品地域
     * @param string $region 地域
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * 获取产品地域
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * 设置认证信息实例
     * @param Credential $credential 认证信息实例
     */
    public function setCredential($credential)
    {
        $this->credential = $credential;
    }

    /**
     * 返回认证信息实例
     * @return Credential
     */
    public function getCredential()
    {
        return $this->credential;
    }

    /**
     * 设置配置实例
     * @param ClientProfile $profile 配置实例
     */
    public function setClientProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * 返回配置实例
     * @return ClientProfile
     */
    public function getClientProfile()
    {
        return $this->profile;
    }

    /**
     * @param string $action  方法名
     * @param array $request  参数列表
     * @return mixed
     * @throws TencentCloudSDKException
     */
    public function __call($action, $request)
    {
        try {
            $responseData = null;
            $serializeRequest = $request[0]->serialize();
            $method = $this->getPrivateMethod($request[0], "arrayMerge");
            $serializeRequest = $method->invoke($request[0], $serializeRequest);
            switch ($this->profile->getHttpProfile()->getReqMethod()) {
                case HttpProfile::$REQ_GET:
                    $responseData = $this->getRequest($action, $serializeRequest);
                    break;
                case HttpProfile::$REQ_POST:
                    $responseData = $this->postRequest($action, $serializeRequest);
                    break;
                default:
                    throw  new TencentCloudSDKException("", "Method only support (GET, POST)");
                    break;
            }
            if ($responseData->getStatusCode() !== AbstractClient::$HTTP_RSP_OK) {
                throw new TencentCloudSDKException($responseData->getReasonPhrase(), $responseData->getBody());
            }
            $tmpResp = json_decode($responseData->getBody(), true)["Response"];
            if (array_key_exists("Error", $tmpResp)) {
                throw new TencentCloudSDKException($tmpResp["Error"]["Code"], $tmpResp["Error"]["Message"], $tmpResp["RequestId"]);
            }

            return $this->returnResponse($action, $tmpResp);
        } catch (\Exception $e) {
            if (!($e instanceof TencentCloudSDKException)) {
                throw new TencentCloudSDKException("", $e->getMessage());
            } else {
                throw $e;
            }
        }
    }

    /**
     * @throws TencentCloudSDKException
     */
    private function getRequest($action, $request)
    {
        $query = $this->formatRequestData($action, $request, httpProfile::$REQ_GET);
        $connect = $this->createConnect();
        return $connect->getRequest($this->path, $query, []);
    }

    /**
     * @throws TencentCloudSDKException
     */
    private function postRequest($action, $request)
    {
        $body = $this->formatRequestData($action, $request, httpProfile::$REQ_POST);
        $body = array('authorization'=>'q-sign-algorithm=sha1&q-ak=AKIDkOrrlYnf2ERxNeyna9Zowq4A4mNnl63p&q-sign-time=1555981551;1555982451&q-key-time=1555981551;1555982451&q-header-list=content-type;user-agent&q-url-param-list=action;envname;eventid;file_list;timestamp&q-signature=618f7aaf28ff37356c806a398a9e208b2f7477c0',
                        'envName'=>'tcbenv-mPIgjhnq',
                        'eventId'=>'1555937477662_35422',
                        'file_list'=> array(array('fileid'=>'cloud://tcbenv-mPIgjhnq.test-13db21/a|b.jpeg')),
                        'path'=>'a|b.jpeg',
                        'sdk_version'=>'1.5.0-beta.1',
                        'timestamp'=>'1555937477663',
                        'action'=>'storage.batchGetDownloadUrl'
                        );
        $connect = $this->createConnect();
        return $connect->postRequest($this->path, [], $body);
    }

    /**
     * @throws TencentCloudSDKException
     */
    private function formatRequestData($action, $request, $reqMethod)
    {
        $param = $request;
        $param["action"] = ucfirst($action);
        $param["sdk_version"] = $this->sdkVersion;
        // $param["Nonce"] = rand();
        $param["timestamp"] = time();
        // $param["Version"] = $this->apiVersion;

        if ($this->credential->getSecretId()) {
            $param["SecretId"] = $this->credential->getSecretId();
        }

        if ($this->region) {
            $param["Region"] = $this->region;
        }

        if ($this->credential->getToken()) {
            $param["Token"] = $this->credential->getToken();
        }

        if ($this->profile->getSignMethod()) {
            $param["SignatureMethod"] = $this->profile->getSignMethod();
        }

        $signStr = $this->formatSignString(
            $this->profile->getHttpProfile()->getEndpoint(),
            $this->path,
            $param,
            $reqMethod
        );
        $param["Signature"] = Sign::sign($this->credential->getSecretKey(), $signStr, $this->profile->getSignMethod());
        return $param;
    }

    private function createConnect()
    {
        return new HttpConnection($this->profile->getHttpProfile()->getProtocol() .
            $this->profile->getHttpProfile()->getEndpoint(), $this->profile);
    }

    private function formatSignString($host, $uri, $param, $requestMethod)
    {
        $tmpParam = [];
        ksort($param);
        foreach ($param as $key => $value) {
            array_push($tmpParam, str_replace("_", ".", $key) . "=" . $value);
        }
        $strParam = join("&", $tmpParam);
        $signStr = strtoupper($requestMethod) . $host . $uri . "?" . $strParam;
        return $signStr;
    }

    private function getPrivateMethod($obj, $methodName)
    {
        $objReflectClass = new ReflectionClass(get_class($obj));
        $method = $objReflectClass->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }
}
