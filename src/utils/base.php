<?php
require_once 'src/sdk/TCloudAutoLoader.php';
require_once 'src/utils/autoload.php';

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Tcb\V20180608\TcbClient as Client;
use TencentCloud\Tcb\V20180608\Models as Models;

class TcbBase
{

    protected $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    public function cloudApiRequest($args)
    {
        $action = $args['action'];

        $config = [];
        $config['secretId'] = $this->config['secretId'];
        $config['secretKey'] = $this->config['secretKey'];

        $params = array_merge([
            'CommParam' => [
                'Module' => $action
            ]
        ], $args['params']);

        if (array_key_exists('envName', $this->config)) {
            $params['CommParam']['EnvName'] = $this->config['envName'];
        }

        

        $cred = new Credential($config['secretId'], $config['secretKey']);
        // $httpProfile = new HttpProfile(null, 'tcb-admin.tencentcloudapi.com');
        $httpProfile = new HttpProfile('http://', 'localhost:8002');
        $clientProfile = new ClientProfile(null, $httpProfile);

        $client = new Client($cred, 'ap-shanghai', $clientProfile);
        
        $actionTran = $action;
        if ($action === 'functions.invokeFunction') {
            $actionTran = 'InvokeFunction';
        }
        if ($action === 'storage.batchGetDownloadUrl') {
            $actionTran = 'GetDownloadUrls';
        }

        $modelAction = 'TencentCloud\\Tcb\\V20180608\\Models\\' . $actionTran . 'Request';
        $req = new $modelAction();

        $req->deserialize($params);

        // mock req
        $mockReq = array('authorization'=>'q-sign-algorithm=sha1&q-ak=AKIDkOrrlYnf2ERxNeyna9Zowq4A4mNnl63p&q-sign-time=1555761300;1555762200&q-key-time=1555761300;1555762200&q-header-list=user-agent&q-url-param-list=action;envname;eventid;path;timestamp&q-signature=36f30221b822ac1a10256574fa5a3ee7141bcfe5',
                        'envName'=>'tcbenv-mPIgjhnq',
                        'eventId'=>'1555937477662_35422',
                        'file_list'=> array(array('fileid'=>'cloud://tcbenv-mPIgjhnq.test-13db21/a|b.jpeg')),
                        'path'=>'a|b.jpeg',
                        'sdk_version'=>'1.5.0-beta.1',
                        'timestamp'=>'1555937477663'
                        );
        
        
        

        // var_dump($req);
        $resp = call_user_func(array($client, $action), $req);
        return $resp;
    }
}
