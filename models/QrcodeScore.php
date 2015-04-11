<?php

namespace app\models;

use Yii;
use yii\helpers\Url;
use yii\base\Model;
use yii\web\BadRequestHttpException;


/*
 * 积分二维码
 */
class QrcodeScore extends Model
{
    /*
     * 获取第三方二维码  url
     */
    private $qrcodeApiUrl = 'http://qr.liantu.com/api.php?';
    /*
     * 积分二维码  token 加密盐
     */
    private $tokenSalt = 'rOewo12LP083xeerx';

    /*
     * 最终的时间参数
     */
    public $_time;
    /*
     * 积分
     */
    public $score;
    /*
     * 积分 时长
     */
    public $duration;
    /*
     * 时间格式
     */
    public $timeFormat;
    

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['score','duration','timeFormat'], 'required'],
            [['score','duration','num'], 'integer'],
            [['timeFormat'],'in','range'=>['s','m','h','d']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'score' => '积分',
            'duration' => '有效时长',
            'timeFormat' => '时长格式'
        ];
    }

    /*
     * 返回 积分的时间戳
     */
    public function getTime()
    {
        if( $this->_time )
            return $this->_time;
        $len = 0;
        switch($this->timeFormat){
            case 's': $len = $this->duration; break;
            case 'm': $len = $this->duration * 60; break;
            case 'h': $len = $this->duration * 3600; break;
            case 'd': $len = $this->duration * 3600*24; break;
        }

        return $this->_time = time() + $len;
    }

    public function getWxid()
    {
        if(! Yii::$app->session['wechat']['id'] )
            throw new BadRequestHttpException('用户过期','403');
        return Yii::$app->session['wechat']['id'];
    }
    /*
     * 返回 token
     */
    public function getToken()
    {
        $rand = ToolBase::getSalt(8);
        return $rand . substr( md5( $this->getTime() . $this->getWxid() . $this->tokenSalt . $rand) , 0 ,16);
    }

    /*
     * 返回 二维码的 图片 链接
     */
    public function getCodeUrl()
    {
        $urlParams = [
            'text' => Url::toRoute([
                'codeapi/score',
                'id'=>$this->getWxid(),
                'score'=>$this->score,
                't'=>$this->getTime(),
                'token'=>$this->getToken()
            ],'http'),
        ];

        return $this->qrcodeApiUrl . http_build_query($urlParams);
    }

}
