<?php

namespace app\controllers;
use app\models\TblWechatMachine;
use app\models\WxBase;
use yii\web\NotFoundHttpException;

class IController extends \yii\web\Controller
{
    public  $layout = 'home';  //使用home布局

    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 我的机器
     */
    public function actionMachine($id)
    {
//        $openid = WxBase::openId($id);
        $openid = 'oXMyut8n0CaEuXxxKv2mkelk_uaY';
        $model = TblWechatMachine::find(['wx_id' => $id, 'openid' => $openid])->asArray()->all();
        // 没有机器，跳转到租借页面
        if(!$model){

        }
        return $this->render('machine',['model'=>$model,'id'=>$id]);
    }

    public function actionScore()
    {
        return $this->render('score');
    }

    public function actionService()
    {
        return $this->render('service');
    }


}
