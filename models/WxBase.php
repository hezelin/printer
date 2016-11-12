<?php

namespace app\models;

use Yii;
use yii\base\InvalidParamException;
use yii\helpers\Url;
/*
 * 微信基本公共库
 */
class WxBase {

    // 微信公众号 id
    public $id;
    // 默认 1个小时,7200
    public static $redisTimeout = 86400;
    /*
     * 获取公众号的 app_id
     * hash 保存 微信的资料
     * app_id,app_secret
     * wx:1 appid
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
    
    public static function appId($id)
    {
        if( !$appid = Yii::$app->cache->get('wx:'.$id.':appid') ){
            $model = TblWeixin::find()
                ->where(['id'=>$id,'enable'=>'Y'])
                ->one();
            Yii::$app->cache->set('wx:'.$id.':appid',$model->app_id,self::$redisTimeout);
            Yii::$app->cache->set('wx:'.$id.':appsecret',$model->app_secret,self::$redisTimeout);

            return $model->app_id;
        }
        return $appid;
    }

    /*
     * 获取公众号 app_secret
     */
    public static function appSecret($id)
    {
        if( !$secret = Yii::$app->cache->get('wx:'.$id.':appsecret') ){
            $model = TblWeixin::find()
                ->where(['id'=>$id,'enable'=>'Y'])
                ->one();
            Yii::$app->cache->set('wx:'.$id.':appid',$model->app_id,7180);
            Yii::$app->cache->set('wx:'.$id.':appsecret',$model->app_secret,7180);
            return $model->app_id;
        }
        return $secret;
    }

    /*
     * 获取用户 openId
     * @params $id 公众号id
     */
    public static function openId($id,$isCache=true,$route=false)
    {
        if( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false )
            return 'oXMyut8n0CaEuXxxKv2mkelk_uaY';

        if(isset( $_GET['openid']) && $_GET['openid'] && strlen($_GET['openid']) == 28)
            return Yii::$app->session['openid_'.$id] = $_GET['openid'];

        if($isCache && isset( Yii::$app->session['openid_'.$id]) )
            return Yii::$app->session['openid_'.$id];

        /*
         * 这里可以加入判断 是否微信来源
         * 如果是 请求下面的链接
         * 如果否 跳转到登录页面
         */
        return Yii::$app->getResponse()->redirect( self::webOpenId($id,'base',$route))->send();
    }

    /*
     * 网页授权获取用户资料，入口，最后返回 openId
     * 判断是否存在 session 里面的openid,
     * 然后判断数据库是否保存了 用户资料
     * 最后拉取授权
     */
    public static function webUser($id,$route=false)
    {
        return Yii::$app->getResponse()->redirect( self::webOpenId($id,'userinfo',$route))->send();
    }

    /*
     * 网页授权获取 openId
     * 返回腾讯授权url和完整参数
     * 回调redirect_uri 为  /openUrl/route?route=当前url
     * $type = base / userinfo
     */
    public static function webOpenId($wx_id,$type='base',$route=false)
    {
        $openUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';
        $url = Url::toRoute(['/openurl/route','route'=>$route? :Yii::$app->request->url,'wx_id'=>$wx_id],true);
        $state = $type;

        $params = [
            'appid'=>self::appId($wx_id),
            'redirect_uri'=>$url,
            'response_type'=>'code',
            'scope'=>'snsapi_' . $type,
            'state'=>$state,
        ];
        // 最后的锚点 #wechat_redirect 不可省略
        return $openUrl.'?'.http_build_query($params).'#wechat_redirect';
    }

    /*
     * 调用微信接口 获取 access_token
     * 并且把结果缓存
     * wx:id:token
     */
    public function setAccessToken()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/token';
        $data = [
            'grant_type'=>'client_credential',
            'appid'=>self::appId($this->id),
            'secret'=>self::appSecret($this->id),
        ];

        $curl = new Curl();
        $req = $curl->getJson($url,$data);
        if(isset($req['access_token']) && $req['access_token']){
            Yii::$app->cache->set('wx:'.$this->id.':token',$req['access_token'],7180);
            return $req['access_token'];
        }

        Yii::$app->end('获取 access_token 失败！');
    }

    /*
     * 获取 accessToken,
     * 判断session 是否存在 和 时间是否过期
     * wxAccessToken 周期为  7200 秒
     */
    public function accessToken($isCache = true)
    {
        if( $isCache && $token = Yii::$app->cache->get('wx:'.$this->id.':token'))
            return $token;
        return $this->setAccessToken();
    }

    /*
     * 创建菜单
     * @parmas $data 为菜单数组
     * 要求不能对中文编码 ，JSON_UNESCAPED_UNICODE
     */
    public function createMenu($name = '')
    {
        if( ! $this->id )
            throw new InvalidParamException('参数错误！');

        $curl = new Curl();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create';

        $req = $curl->postJson($url,
            json_encode( $this->menu($name),JSON_UNESCAPED_UNICODE),
            array('access_token'=>$this->accessToken())
        );
        return $req['errmsg'] == 'ok' ? true: false;
    }


    /*
     * 微信餐单
     */
    public function menu($name)
    {
        return [
            'button'=>[
                [
                    'name'=>'商城',
                    'type'=>'view',
                    'url'=>Url::toRoute(['/shop/item/list','id'=>$this->id],true)
                ],
                [
                    'name'=>'服务',
                    'type'=>'view',
                    'url'=>Url::toRoute(['wechat/index','id'=>$this->id],true)
                ],
                [
                    'name'=>'一键报修',
                    'type'=>'scancode_push',
                    'key'=>'rselfmenu_0_1',
                ]
            ],
        ];
    }

    public $errorCode = [
        '-1' => '系统繁忙',
        '0' => '请求成功',
        '40001' => '获取access_token时AppSecret错误，或者access_token无效',
        '40002' => '不合法的凭证类型',
        '40003' => '不合法的OpenID',
        '40004' => '不合法的媒体文件类型',
        '40005' => '不合法的文件类型',
        '40006' => '不合法的文件大小',
        '40007' => '不合法的媒体文件id',
        '40008' => '不合法的消息类型',
        '40009' => '不合法的图片文件大小',
        '40010' => '不合法的语音文件大小',
        '40011' => '不合法的视频文件大小',
        '40012' => '不合法的缩略图文件大小',
        '40013' => '不合法的APPID',
        '40014' => '不合法的access_token',
        '40015' => '不合法的菜单类型',
        '40016' => '不合法的按钮个数',
        '40017' => '不合法的按钮个数',
        '40018' => '不合法的按钮名字长度',
        '40019' => '不合法的按钮KEY长度',
        '40020' => '不合法的按钮URL长度',
        '40021' => '不合法的菜单版本号',
        '40022' => '不合法的子菜单级数',
        '40023' => '不合法的子菜单按钮个数',
        '40024' => '不合法的子菜单按钮类型',
        '40025' => '不合法的子菜单按钮名字长度',
        '40026' => '不合法的子菜单按钮KEY长度',
        '40027' => '不合法的子菜单按钮URL长度',
        '40028' => '不合法的自定义菜单使用用户',
        '40029' => '不合法的oauth_code',
        '40030' => '不合法的refresh_token',
        '40031' => '不合法的openid列表',
        '40032' => '不合法的openid列表长度',
        '40033' => '不合法的请求字符，不能包含\uxxxx格式的字符',
        '40035' => '不合法的参数',
        '40038' => '不合法的请求格式',
        '40039' => '不合法的URL长度',
        '40050' => '不合法的分组id',
        '40051' => '分组名字不合法',
        '41001' => '缺少access_token参数',
        '41002' => '缺少appid参数',
        '41003' => '缺少refresh_token参数',
        '41004' => '缺少secret参数',
        '41005' => '缺少多媒体文件数据',
        '41006' => '缺少media_id参数',
        '41007' => '缺少子菜单数据',
        '41008' => '缺少oauth code',
        '41009' => '缺少openid',
        '42001' => 'access_token超时',
        '42002' => 'refresh_token超时',
        '42003' => 'oauth_code超时',
        '43001' => '需要GET请求',
        '43002' => '需要POST请求',
        '43003' => '需要HTTPS请求',
        '43004' => '需要接收者关注',
        '43005' => '需要好友关系',
        '44001' => '多媒体文件为空',
        '44002' => 'POST的数据包为空',
        '44003' => '图文消息内容为空',
        '44004' => '文本消息内容为空',
        '45001' => '多媒体文件大小超过限制',
        '45002' => '消息内容超过限制',
        '45003' => '标题字段超过限制',
        '45004' => '描述字段超过限制',
        '45005' => '链接字段超过限制',
        '45006' => '图片链接字段超过限制',
        '45007' => '语音播放时间超过限制',
        '45008' => '图文消息超过限制',
        '45009' => '接口调用超过限制',
        '45010' => '创建菜单个数超过限制',
        '45015' => '回复时间超过限制',
        '45016' => '系统分组，不允许修改',
        '45017' => '分组名字过长',
        '45018' => '分组数量超过上限',
        '46001' => '不存在媒体数据',
        '46002' => '不存在的菜单版本',
        '46003' => '不存在的菜单数据',
        '46004' => '不存在的用户',
        '47001' => '解析JSON/XML内容错误',
        '48001' => 'api功能未授权',
        '50001' => '用户未授权该api',
    ];
}