<?php
namespace app\models;

use Yii;
use yii\helpers\Url;

/*
 * redis 缓存工具
 * @author harry
 * @time 2015-4-27
 */

class Cache
{

    /*
     * 设置过期时间 秒
     */
    public static function setValue($key,$value,$expire = 0)
    {
        if ($expire == 0)
            return (bool) Yii::$app->redis->executeCommand('SET', [$key, $value]);
        else
            return (bool) Yii::$app->redis->executeCommand('SET', [$key, $value, 'PX', $expire*1000 ]);
    }

    public static function getValue($key)
    {
        return Yii::$app->redis->executeCommand('GET', [$key]);
    }

    public static function delValue($key)
    {
        return Yii::$app->redis->executeCommand('DEL', [$key]);
    }
    /*
     * 获取 微信id
     * 跳转登录
     * 跳转选择公众号
     * $key = 'u:'.Yii::$app->user->id.':wid';
     */
    public static function getWid()
    {
        if( !Yii::$app->user->id)
            return Yii::$app->getResponse()->redirect(Url::toRoute(['auth/login','url'=>Yii::$app->request->url]));
        $value = Yii::$app->redis->executeCommand('GET', ['u:'.Yii::$app->user->id.':wid']);
        if( empty($value) ){
            return Yii::$app->getResponse()->redirect(Url::toRoute(['weixin/select','url'=>Yii::$app->request->url]));
        }
        return $value;
    }
} 