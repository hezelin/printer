<?php

namespace app\controllers;

use app\models\TblMachineService;
use app\models\TblRentApply;
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
     * $id 机器id,
     */
    public function actionMachine($id)
    {
        $wid = TblRentApply::find()->select('wx_id')->where(['machine_id'=>$id,'enable'=>'Y'])->scalar();
        $openid = WxBase::openId($wid);

        if(!$this->checkMaintain($openid)){             // 维修员页面跳转
//            查看维修状态
            $model = TblMachineService::find()
                ->where(['machine_id'=>$id,'openid'=>$openid,'enable'=>'Y'])
                ->andWhere(['<','status',9])
                ->one();
            if(!$model){                                 // 没有维修申请，机器信息、录入机器坐标
                return $this->render('firstmachine',['id'=>$id,'wid'=>$wid]);

            }


            return $this->render('machine',['id'=>$id]);

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
    private function checkMaintain($openid)
    {
        return TblUserMaintain::findOne(['openid'=>$openid])? false:true;
    }

    /*
     * 跳转维修员页面
     */
    private function maintain()
    {

    }

    /*
     * 跳转客户扫描页面
     */
    private function user()
    {

    }
}
