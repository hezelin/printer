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
            return Yii::$app->redis->set($key,$value);
        else
            return Yii::$app->redis->set($key,$value,$expire);
    }

    public static function getValue($key)
    {
        return Yii::$app->redis->get($key);
    }

    public static function delValue($key)
    {
        return Yii::$app->redis->delete($key);
    }


    /*
     * 获取 微信id
     * 跳转登录
     * 跳转选择公众号
     * $key = 'u:'.Yii::$app->user->id.':wid';
     */
    public static function getWid()
    {
        if( Yii::$app->user->isGuest )
            return Yii::$app->getResponse()->redirect(['auth/login','url'=>Yii::$app->request->url])->send();

        $value = Yii::$app->redis->get('u:'.Yii::$app->user->id.':wid');
        if( empty($value) )
        {
            if( Yii::$app->user->identity->group_id > 0)
            {
                Yii::$app->redis->set('u:'.Yii::$app->user->id.':wid',Yii::$app->user->identity->weixin_id);
                return Yii::$app->user->identity->weixin_id;
            }else{
                if( Yii::$app->request->get('wx_id') )
                {
                    Yii::$app->redis->set('u:'.Yii::$app->user->id.':wid',Yii::$app->request->get('wx_id'));
                    return Yii::$app->request->get('wx_id');
                }
            }

            return Yii::$app->getResponse()->redirect(['weixin/select','url'=>Yii::$app->request->url])->send();
        }

        return $value;
    }
} 