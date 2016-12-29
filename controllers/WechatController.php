<?php

namespace app\controllers;
use app\models\TblUserMaintain;
use app\models\TblStoreSetting;
use yii\web\NotFoundHttpException;
use app\models\WxBase;

class WechatController extends \yii\web\Controller
{
    public $layout = 'auicss';        //使用home布局
    /*
     * 微官网，判断是否微信人员
     */
    public function actionIndex($id)
    {
        $openid = WxBase::openId($id);
        $setting = TblStoreSetting::find()
            ->where(['enable'=>'Y','wx_id'=>$id])
            ->with('carousel')
            ->limit(5)
            ->asArray()
            ->one();

        if($setting == null)
            throw new NotFoundHttpException('您所访问的页面不存在');
        if(!$this->checkMaintain($id,$openid)){             // 维修员页面跳转订
//            查询 系统订单数
            $num['order'] = (new \yii\db\Query())
                ->select('count(*)')
                ->from('tbl_machine_service')
                ->where(['status'=>1,'weixin_id'=>$id])
                ->scalar();
            $num['new'] = (new \yii\db\Query())
                ->select('count(*)')
                ->from('tbl_notify_log')
                ->where('enable="Y" and is_read="N" and openid=:openid and wx_id=:wid',[':openid'=>$openid,':wid'=>$id])
                ->scalar();
            $num['fault'] = (new \yii\db\Query())
                ->select('count(*)')
                ->from('tbl_machine_service')
                ->where('status<9 and openid=:openid and weixin_id=:wid',[':openid'=>$openid,':wid'=>$id])
                ->scalar();

            return $this->render('maintain',['setting'=>$setting,'num'=>$num,'openid'=>$openid]);
        }
        $phone = (new \yii\db\Query())
            ->select('phone')
            ->from('tbl_store_setting')
            ->where(['wx_id'=>$id])
            ->scalar();

        return $this->render('index',[
            'setting'=>$setting,
            'phone'=>$phone
        ]);
    }

    /*
     * 检查是否维修员
     */
    private function checkMaintain($id,$openid)
    {
        //20161228 biao 维修员表：新增状态字段
        return TblUserMaintain::findOne(['wx_id'=>$id,'openid'=>$openid, 'status' => 10])? false:true;
    }

}
