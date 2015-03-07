<?php

namespace app\models;

use Yii;
/*
 * 微信基本公共库
 */
class WxBase {

    /*
     * 获取用户 openId
     */
    public static function openId()
    {

        if( isset($_GET['openId']) && $_GET['openId']){
            Yii::app()->session['openId'] = $_GET['openId'];
            return $_GET['openId'];
        }

        if(isset( Yii::app()->session['openId']) )
            return Yii::app()->session['openId'];

        /*
         * 这里可以加入判断 是否微信来源
         * 如果是 请求下面的链接
         * 如果否 跳转到登录页面
         */
        header('Location: '.self::webOpenId(), true, 302);

    }

    /*
     * 网页授权获取 openId
     * 返回腾讯授权url和完整参数
     * 回调redirect_uri 为  /openUrl/route?route=当前url
     */
    public static function webOpenId()
    {

        $openUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';
        $url = Yii::app()->request->hostInfo.'/openUrl/route?route='.Yii::app()->request->url;
        $state = rand(10000,99999);             // 自定义标记，微信传回的数值

        $params = array(
            'appid'=>Yii::app()->params['appId'],
            'redirect_uri'=>$url,
            'response_type'=>'code',
            'scope'=>'snsapi_base',
//            'scope'=>'snsapi_userinfo',
            'state'=>$state,
        );
        // 最后的锚点 #wechat_redirect 不可省略
        return $openUrl.'?'.http_build_query($params).'#wechat_redirect';
    }

    /*
 * 调用微信接口 获取 access_token
 * 并且把结果缓存
 */
    public static  function setAccessToken()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token';
        $data = array(
            'grant_type'=>'client_credential',
            'appid'=>Yii::app()->params['appId'],
            'secret'=>Yii::app()->params['appSecret'],
        );
        $req = Yii::app()->curl->getJson($url,$data);
        if(isset($req['access_token']) && $req['access_token']){
            Yii::app()->session['wxAccessTokenTime'] =time();
            Yii::app()->session['wxAccessToken'] = $req['access_token'];
            return $req['access_token'];
        }

        Yii::app()->end('获取 access_token 失败！');
    }

    /*
     * 获取 accessToken,
     * 判断session 是否存在 和 时间是否过期
     * wxAccessToken 周期为  7200 秒
     */
    public static  function accessToken($isCache = true)
    {
        if($isCache && Yii::app()->session['wxAccessTokenTime'] && Yii::app()->session['wxAccessToken'] && (time()-Yii::app()->session['wxAccessTokenTime']) < 7200 )
            return Yii::app()->session['wxAccessToken'];
        return self::setAccessToken();
    }

    /*
     * 创建菜单
     * @parmas $data 为菜单数组
     * 要求不能对中文编码 ，JSON_UNESCAPED_UNICODE
     */
    public static  function createMenu()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create';

        return Yii::app()->curl->postJson($url,
            json_encode(self::menu(),JSON_UNESCAPED_UNICODE),
            array('access_token'=>self::accessToken())
        );
    }

    /*
     * 微信餐单
     */
    public static function menu()
    {
        return array(
            'button'=>array(
                array(
                    'name'=>'参赛作品',
                    'type'=>'view',
                    'url'=>Yii::app()->request->hostInfo.'/works/show',
                ),
                array(
                    'name'=>'报名竞赛',
                    'sub_button'=>array(
                        /*array(
                            'type'=>'view',
                            'name'=>'报名指南',
                            'url'=>Yii::app()->request->hostInfo.'/apply/guide',
                        ),*/
                        array(
                            'type'=>'view',
                            'name'=>'点击报名',
                            'url'=>Yii::app()->request->hostInfo.'/project/index',
                        ),
                        array(
                            'type'=>'view',
                            'name'=>'上传作品',
                            'url'=>Yii::app()->request->hostInfo.'/apply/upload2',
                        ),
                        array(
                            'type'=>'view',
                            'name'=>'我的资料',
                            'url'=>Yii::app()->request->hostInfo.'/i/info',
                        ),
                        array(
                            'type'=>'view',
                            'name'=>'关于我们',
                            'url'=>Yii::app()->request->hostInfo.'/us/about',
                        ),
                        /*array(
                            'type'=>'view',
                            'name'=>'我的报名',
                            'url'=>Yii::app()->request->hostInfo.'/i/apply',
                        ),*/
                    )
                ),
                array(
                    'name'=>'我的吐槽',
                    'type'=>'view',
                    'url'=>Yii::app()->request->hostInfo.'/share/tmp',
//                    'url'=>'http://m.wsq.qq.com/264141487',
                ),
                /*array(
                    'name'=>'我的',
                    'sub_button'=>array(
                        array(
                            'type'=>'view',
                            'name'=>'我的投票',
                            'url'=>Yii::app()->request->hostInfo.'/i/vote',

                        ),
                        array(
                            'type'=>'view',
                            'name'=>'我的报名',
                            'url'=>Yii::app()->request->hostInfo.'/i/apply',

                        ),
                        array(
                            'type'=>'view',
                            'name'=>'我的资料',
                            'url'=>Yii::app()->request->hostInfo.'/i/info',
                        ),
                        array(
                            'type'=>'view',
                            'name'=>'共同参与',
                            'url'=>'http://m.wsq.qq.com/264141487',
                        )
                    ),
                ),*/
            ),
        );
    }
}