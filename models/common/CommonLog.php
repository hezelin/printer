<?php
/**
 * 全局记录
 * 用户登录
 */
namespace app\models\common;
use app\models\LogUserLogin;
use Yii;

class CommonLog {

    /*
     * 记录登录
     */
    public static function login()
    {
        if(Yii::$app->user->isGuest)
            return false;
        $model = new LogUserLogin();
        $model->user_id = Yii::$app->user->id;
        $model->group_id = Yii::$app->user->identity->group_id;
        $model->login_ip = Yii::$app->request->userIP;
        $model->user_agent = Yii::$app->request->userAgent;
        $model->login_time = time();
        return $model->save();
    }
}