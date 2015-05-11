<?php
namespace app\controllers;

use app\models\Curl;
use app\models\ToolBase;
use yii\helpers\Url;
use app\models\WxBase;
use Yii;
use yii\web\BadRequestHttpException;

class OpenurlController extends \yii\web\Controller
{
    /*
     * 微信网页授权 回调页面
     * 可获取到 openid ，并且跳转会 route 参数的来源 url
     * 防住死循环，增加一个参数 request_num = 1;
     */
    public function actionRoute($id)
    {
        if(isset($_GET['request_num']) && $_GET['request_num'] >= 1 )
            throw new BadRequestHttpException('不合法请求');

        if($_GET['code'])
        {
            $route = $_GET['route'];

            $param2 = array(
                'appid'=>WxBase::appId($id),
                'secret'=>WxBase::appSecret($id),
                'grant_type'=>'authorization_code',
                'code'=>$_GET['code'],
            );

            $curl = new Curl();
            $data2 = $curl->getJson('https://api.weixin.qq.com/sns/oauth2/access_token',$param2);

            /*
             * 拉取用户资料
             */
            if($_GET['state'] == 'userinfo'){
                $userinfo = $curl->get('https://api.weixin.qq.com/sns/userinfo',[
                    'access_token'=>$data2['access_token'],
                    'openid'=>$data2['openid'],
                    'lang'=>'zh_CN'
                ]);
                print_r($userinfo);
                exit;
            }

            Yii::$app->session->set('openid',$data2['openid']);

            $this->redirect( ToolBase::url($route,['openid'=>$data2['openid'],'request_num'=>1]) );
        }
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
}

