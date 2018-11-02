<?php
require_once 'src/sdk/TCloudAutoLoader.php';
require_once 'src/utils/autoload.php';

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Tcb\V20180608\TcbClient as Client;
use TencentCloud\Tcb\V20180608\Models as Models;

class TcbBase {

    protected $config;

    function __construct($config) {
        $this->config = $config;
    }

    public function cloudApiRequest($args) {
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
        $httpProfile = new HttpProfile(null, 'tcb.tencentcloudapi.com');
        $clientProfile = new ClientProfile(null, $httpProfile);
    
        $client = new Client($cred, 'ap-shanghai', $clientProfile);
    
        $modelAction = 'TencentCloud\\Tcb\\V20180608\\Models\\'.$action.'Request';
        $req = new $modelAction();
    
        $req->deserialize($params);
    
        // var_dump($req);
        $resp = call_user_func(array($client, $action), $req);
        return $resp;
    }
}
?>