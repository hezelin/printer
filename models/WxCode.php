<?php
/**
 * 微信二维码
 */

namespace app\models;
use Yii;
use yii\helpers\Url;

class WxCode extends WxBase {
    /**
     * 创建QR二维码
     * 调用的参数示例
     * $params = [
     *     // 二维码类型，QR_SCENE为临时,QR_LIMIT_SCENE为永久,QR_LIMIT_STR_SCENE为永久的字符串参数值,默认为QR_SCENE
     *     'action_name' => 'QR_SCENE',
     *     'action_info' => [
     *         'scene' => [
     *             // 场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
     *             'scene_id' => $sceneId,
     *             // 场景值ID（字符串形式的ID），字符串类型，长度限制为1到64，仅永久二维码支持此字段
     *             'scene_str' => $sceneStr
     *         ]
     *     ]
     * ];
     *
     * @params $params = ['scene_id'=>223']
     */
    public function qrCode(array $params,$type = 'QR_SCENE',$expire = 1800,$imgUrl = true)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create';
        $curl = new Curl();
        $params = [
            'expire_seconds' => $expire,
            'action_name' => $type,
            'action_info'=>[
                'scene'=>$params
            ]
        ];

        $req = $curl->postJson($url,json_encode($params,JSON_UNESCAPED_UNICODE),['access_token'=>$this->accessToken()]);
        if(!isset($req['ticket']))
            return false;
        if( !$imgUrl)
            return $req['url'];

        return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.urlencode($req['ticket']);
    }

    /*
     * 返回 绑定维修员的二维码图片
     * 绑定为维修员 场景值为 1
     * 返回图片的链接
     */
    public function bindMaintainCode()
    {
        return $this->qrCode(['scene_id'=>1]);
    }

    /*
     * 生成 永久积分二维码
     *
     */
    public function scoreCode()
    {
        return $this->qrCode(['scene_id'=>2],'QR_LIMIT_SCENE');
    }
} 