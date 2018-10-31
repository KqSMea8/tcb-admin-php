<?php
require_once 'src/utils/base.php';
require_once 'src/consts/code.php';

use TencentCloud\Common\Exception\TencentCloudSDKException;

class TcbStorage extends TcbBase {

    protected $config;

    function __construct($config) {
        parent::__construct($config);
    }

    public function getTempFileURL($options = []) {

        if (!array_key_exists('fileList', $options) || !is_array($options['fileList'])) {
            throw new TencentCloudSDKException(INVALID_PARAM, '参数fileList类型必须是数据');
        }

        $fileList = $options['fileList'];
        $processFiles = array();

        foreach ($fileList as $file) {
            if (is_array($file)) {
                if (
                    !array_key_exists('fileID', $file) ||
                    !array_key_exists('maxAge', $file)
                  ) {
                    throw new TencentCloudSDKException(INVALID_PARAM, 'fileList的元素必须是包含fileID和maxAge的对象');
                  }
                
                array_push($processFiles, array(
                    'FileId' => $file['fileID'],
                    'TTL' => $file['maxAge']
                ));
            }
            elseif (is_string($file)) {
                array_push($processFiles, array(
                    'FileId' => $file
                ));
            }
            else {
                throw new TencentCloudSDKException(INVALID_PARAM, 'fileList的元素必须是字符串');
            }
        }

        $args = array();
        $args['action'] = 'GetDownloadUrls';

        $args['params'] = array(
            'Files' => $processFiles
        );

        $result = $this->cloudApiRequest($args);

        // 如果 code 和 message 存在，证明报错了
        if (property_exists($result, 'code')) {
            throw new TencentCloudSDKException($result->code, $result->message, $result->RequestId);
        }

        $tmpFiles = [];

        foreach ($result->DownloadList as $file) {
            $tmpFiles = array_merge($tmpFiles, [
                [
                    'code' => $file->Code,
                    'fileID' => $file->FileId,
                    'tempFileURL' => $file->DownloadUrl
                ]
            ]);
        }

        return [
            'fileList' => $tmpFiles,
            'requestId' => $result->RequestId
        ];
    }

    public function deleteFile($options = []) {
        
        if (!array_key_exists('fileList', $options) || !is_array($options['fileList'])) {
            throw new TencentCloudSDKException(INVALID_PARAM, '参数fileList类型必须是数据');
        }

        $fileList = $options['fileList'];
        
        foreach ($fileList as $file) {
            if (!is_string($file)) {
                throw new TencentCloudSDKException(INVALID_PARAM, 'fileList的元素必须是非空的字符串');
            }
        }

        $args = array();
        $args['action'] = 'DeleteFiles';

        $args['params'] = array(
            'FileIds' => $fileList
        );

        try {
            $result = $this->cloudApiRequest($args);

            // 如果 code 和 message 存在，证明报错了
            if (property_exists($result, 'code')) {
                throw new TencentCloudSDKException($result->code, $result->message, $result->RequestId);
            }

            $tmpFiles = [];

            foreach ($result->DeletedFiles as $file) {
                $tmpFiles = array_merge($tmpFiles, [
                    [
                        'code' => $file->Code,
                        'fileID' => $file->FileId
                    ]
                ]);
            }

            return [
                'fileList' => $tmpFiles,
                'requestId' => $result->RequestId
            ];

        }
        catch (Exception $e) {
            throw new TencentCloudSDKException($e->getErrorCode(), $e->getMessage());
        }

    }

    public function downloadFile($options = []) {

        $fileID = $options['fileID'];
        $tempFilePath = array_key_exists('tempFilePath', $options) ?  $options['tempFilePath'] : null;

        $tmpUrlRes = $this->getTempFileURL([
            'fileList' => [
                [
                    'fileID' => $fileID,
                    'maxAge' => 600
                ]
            ]
        ]);

        if (count($tmpUrlRes['fileList']) == 0) {
            return [
                'code' => 'NO_FILE_EXISTS',
                'message'=> '没有获取任何文件'
            ];
        }

        $res = $tmpUrlRes['fileList'][0];

        if ($res['code'] != 'SUCCESS') {
            throw $res;
        }

        $tmpUrl = $res['tempFileURL'];

        try {
            $file = file_get_contents($tmpUrl);

            if (isset($tempFilePath)) {
                file_put_contents($tempFilePath, $file);
                return [
                    'requestId' => $tmpUrlRes['requestId']
                ];
            }
            else {
                return [
                    'fileContent' => $file,
                    'requestId' => $tmpUrlRes['requestId']
                ];
            }
        }
        catch (Exception $e) {
            throw new TencentCloudSDKException($e->getErrorCode(), $e->getMessage());
        }
    }
}


?>