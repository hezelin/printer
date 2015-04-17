<?php
namespace app\controllers;
use app\models\Curl;


class OpenurlController extends \yii\web\Controller
{
    /*
     * 微信网页授权 回调页面
     * 可获取到 openid ，并且跳转会 route 参数的来源 url
     */
    public function actionRoute()
    {

        if($_GET['code'])
        {
            $route = $_GET['route'];
            $store_id = substr(strrchr($route, '/'), 1);
            $appData = $this->getAppSecret($store_id);
            $param2 = array(
                'appid'=>$appData['appId'],
                'secret'=>$appData['appSecret'],
                'grant_type'=>'authorization_code',
                'code'=>$_GET['code'],
            );

            $curl = new Curl();
            $data2 = $curl->getJson('https://api.weixin.qq.com/sns/oauth2/access_token',$param2);

            header('Location: '.$route.'?openId='.$data2['openid'], true, 302);
        }

        $url = 'http://app.chebao360.com/openUrl/route?route='.urlencode('http://app.chebao360.com/cart/list/9');
//        $url = 'http://app.chebao360.com/openUrl/route';
        $params = array(
            'appid'=>'wx834acfe59f25eb80',
            'redirect_uri'=>$url,
            'response_type'=>'code',
            'scope'=>'snsapi_base',
            'state'=>1,
        );
        // 最后的锚点 #wechat_redirect 不可省略
        $openUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';

        echo $openUrl.'?'.http_build_query($params).'#wechat_redirect';
    }
    public function actionCode()
    {
        echo '<pre>';
        print_r($_GET);

        if($_GET['code'])
        {
            echo 'xxxxxx';
            $param2 = array(
                'appid'=>'wx834acfe59f25eb80',
                'secret'=>'7c3165b263ce0d7c9658f2ac8ef48c57',
                'grant_type'=>'authorization_code',
                'code'=>$_GET['code'],
            );
            $data2 = Yii::$app->curl->get('https://api.weixin.qq.com/sns/oauth2/access_token',$param2);

            print_r($data2);
        }
        $openUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';
        $appid = 'wx834acfe59f25eb80';     // app 唯一id
        $redirect_uri = 'http://app.chebao360.com/test/code';
        $response_type = 'code';
//        $scope = 'snsapi_base';
        $scope = 'snsapi_userinfo';
        $state = rand(10000,99999);             // 自定义标记，微信传回的数值

        $params = array(
            'appid'=>$appid,
            'redirect_uri'=>$redirect_uri,
            'response_type'=>$response_type,
            'scope'=>$scope,
            'state'=>$state,
        );
        // 最后的锚点 #wechat_redirect 不可省略
        echo $openUrl.'?'.http_build_query($params).'#wechat_redirect';
    }


    public function actionXiche()
    {
        $openId = isset($_GET['openId'])? $_GET['openId']:md5(time());

        // 模拟微信访问
        Yii::$app->curl->setOption(CURLOPT_REFERER,'https://mp.weixin.qq.com');
        Yii::$app->curl->setOption(CURLOPT_USERAGENT,'Mozilla/5.0 (iPhone; CPU iPhone OS 6_1_3 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Mobile/10B329 MicroMessenger/5.0.1');

//        echo Yii::$app->curl->get('http://218.244.144.148/test/hd_xc.php',array('openid'=>$openId) );
        $html = Yii::$app->curl->get('http://218.244.144.148/test/hd_xc.php',array('openid'=>$openId));
        $html = str_replace(
            array(
                'href="',
                './',
                'src="'
            ),
            array(
                'href="http://218.244.144.148/test/',
                'http://218.244.144.148/test/',
                'src="http://218.244.144.148/test/'
            ),
            $html
        );

        $html = str_replace(
            array(
                '<link rel="apple-touch-icon-precomposed" href="http://218.244.144.148/test/http://192.168.1.127/mp/apple-touch-icon.png">',
                'href="http://218.244.144.148/test/my7.css"'
            ),
            array(
                '<link rel="apple-touch-icon-precomposed" href="http://192.168.1.127/mp/apple-touch-icon.png">',
                'href="http://218.244.144.148/test/hd/xc/my7.css"'
            ),
            $html
        );

        echo $html;
    }

    public function getSecret()
    {
        if(isset( Yii::$app->session['storeId']) )
            return Yii::$app->session['storeId'];
    }


    public function getAppSecret($storeId)
    {
//        if(isset( Yii::$app->session['appId'],Yii::$app->session['appSecret']) )
//            return array('appSecret'=>Yii::$app->session['appSecret'],'appId'=>Yii::$app->session['appId']) ;

        $data = Yii::$app->db->createCommand()
            ->select('app_id as appId,app_secret as appSecret')
            ->from('tbl_store')
            ->where('id=:id',array(':id'=>$storeId))
            ->queryRow();

//        if($data){
//            Yii::$app->session['appId'] = $data['appId'];
//            Yii::$app->session['appSecret']
//        }

        return $data;
    }


}

