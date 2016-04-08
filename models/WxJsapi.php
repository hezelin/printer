<?php
/**
 * Created by harry
 * 封装 微信的 js-sdk
 */

namespace app\models;
use Yii;

class WxJsapi extends WxBase {

    /*
     * jsapi_ticket ，微信js-sdk
     */
    public function jsTicket($isCache = true)
    {
        if( $isCache && $ticket = Yii::$app->cache->get('wx:'.$this->id.':ticket'))
            return $ticket;

        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';
        $data = [
            'type'=>'jsapi',
            'access_token'=>$this->accessToken(),
        ];

        $curl = new Curl();
        $req = $curl->getJson($url,$data);
        if(isset($req['ticket']) && $req['ticket']){
            Yii::$app->cache->set('wx:'.$this->id.':ticket',$req['ticket'],7180);
            return $req['ticket'];
        }else
            Yii::$app->end('获取 ticket 失败！'.$req['errmsg']);

    }

    /*
     * 生成 wx.config 文件
     * wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: '', // 必填，公众号的唯一标识
            timestamp: , // 必填，生成签名的时间戳
            nonceStr: '', // 必填，生成签名的随机串
            signature: '',// 必填，签名，见附录1
            jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
        });
     */
    public function jsConfig($list = [],$debug = false)
    {
        $time = time();
        $nonceStr = ToolBase::getSalt(16);

        $data = [
            'nonceStr'      => $nonceStr,
            'timestamp'     => $time,
            'signature'     => $this->signature($time,$nonceStr),
            'debug'         => $debug,
            'appId'         => parent::appId($this->id),
            'jsApiList'     => $list? : $this->apiList
        ];

        return 'wx.config('.json_encode($data,JSON_PRETTY_PRINT).');';

    }

    /*
     * 获取签名
     * 数组的 顺序不能变,并且都是小写
     */
    public function signature($time,$nonceStr)
    {
        $data = [
            'jsapi_ticket'  => $this->jsTicket(),
            'noncestr'      => $nonceStr,
            'timestamp'     => $time,
            'url'           => Yii::$app->request->hostInfo.Yii::$app->request->url,
        ];
        $str = '';
        foreach($data as $k => $v)
            $str .= "$k=$v&";

        return sha1( substr($str,0,-1) );
    }

    public $apiList = [
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'startRecord',
        'stopRecord',
        'onVoiceRecordEnd',
        'playVoice',
        'pauseVoice',
        'stopVoice',
        'onVoicePlayEnd',
        'uploadVoice',
        'downloadVoice',
        'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage',
        'translateVoice',
        'getNetworkType',
        'openLocation',
        'getLocation',
        'hideOptionMenu',
        'showOptionMenu',
        'hideMenuItems',
        'showMenuItems',
        'hideAllNonBaseMenuItem',
        'showAllNonBaseMenuItem',
        'closeWindow',
        'scanQRCode',
        'chooseWXPay',
        'openProductSpecificView',
        'addCard',
        'chooseCard',
        'openCard',
    ];
} 