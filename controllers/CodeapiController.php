<?php

namespace app\controllers;

use app\models\WxBase;
use app\models\TblUserMaintain;

class CodeapiController extends \yii\web\Controller
{
    public $layout = 'home';
    public function actionBind()
    {
        return $this->render('bind');
    }

    /*
     * 机器二维码扫描入口
     * 用户扫描 + 维修员扫描
     */
    public function actionMachine($id)
    {
        $openid = WxBase::openId($id);

        if(!$this->checkMaintain($id,$openid)){             // 维修员页面跳转
            return $this->render('machine');
        }

        return $this->render('user',['id'=>$id]);

    }

    public function actionScore()
    {
        return $this->render('score');
    }

    /*
     * 检查是否维修员
     */
    private function checkMaintain($id,$openid)
    {
        return TblUserMaintain::findOne(['wx_id'=>$id,'openid'=>$openid])? false:true;
    }
}
