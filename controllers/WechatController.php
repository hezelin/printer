<?php

namespace app\controllers;
use app\models\TblUserMaintain;
use app\models\TblStoreSetting;
use yii\web\NotFoundHttpException;
use app\models\WxBase;

class WechatController extends \yii\web\Controller
{
    public $layout = 'home';        //使用home布局
    /*
     * 微官网，判断是否微信人员
     */
    public function actionIndex($id)
    {
        $openid = WxBase::openId($id);
//        $openid = 'oXMyut8n0CaEuXxxKv2mkelk_uaY';

        $setting = TblStoreSetting::find(['enable'=>'Y','wx_id'=>$id])
            ->with('carousel')->limit(5)->asArray()->one();

        if($setting == null)
            throw new NotFoundHttpException('您所访问的页面不存在');

        if(!$this->checkMaintain($id,$openid)){             // 维修员页面跳转
            return $this->render('maintain',['setting'=>$setting]);
        }

        return $this->render('index',['setting'=>$setting]);
    }

    public function actionMachinelist($id)
    {

        return $this->render('machinelist');
    }

    /*
     * 检查是否维修员
     */
    private function checkMaintain($id,$openid)
    {
        return TblUserMaintain::findOne(['wx_id'=>$id,'openid'=>$openid])? false:true;
    }

}
