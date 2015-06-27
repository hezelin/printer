<?php
namespace app\controllers;

use app\models\Curl;
use app\models\ToolBase;
use app\models\WxBase;
use Yii;
use yii\web\BadRequestHttpException;

class OpenurlController extends \yii\web\Controller
{
    /*
     * 微信网页授权 回调页面
     * 可获取到 openid ，并且跳转会 route 参数的来源 url
     * 防死循环，增加一个参数 request_num = 1;
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
                return $this->redirect( ToolBase::url($route,json_decode($userinfo,true)) );
            }

            Yii::$app->session->set('openid',$data2['openid']);

            return $this->redirect( ToolBase::url($route,['openid'=>$data2['openid'],'request_num'=>1]) );
        }
    }
}

