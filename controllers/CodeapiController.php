<?php

namespace app\controllers;

use app\models\TblMachineService;
use app\models\TblRentApply;
use app\models\WxBase;
use app\models\TblUserMaintain;
use app\models\ConfigBase;
use yii\helpers\Html;
use yii\helpers\Url;

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

        if(!$this->checkMaintain($openid)) {             // 维修员页面跳转
//            查看维修状态
            $model = TblMachineService::find()
                ->where(['machine_id' => $id, 'openid' => $openid, 'enable' => 'Y'])
                ->andWhere(['<', 'status', 9])
                ->one();
            if (!$model) {                                 // 没有维修申请，机器信息、录入机器坐标
                return $this->render('firstmachine', ['id' => $id, 'wid' => $wid]);
            }
            $status = $model['status'];
            switch ($status) {
                case 3:
                case 6:
                case 7:
                    return $this->render('machine', [
                        'openid' => $openid,
                        'service_id' => $model['id'],
                        'mid' => $id,
                        'wid' => $wid,
                        'btnHtml' => Html::a(
                            ConfigBase::getFixMaintainStatus($status),
                            Url::toRoute(['m/processajax', 'id' => $model['id'], 'openid' => $openid]),
                            [
                                'data-ajax' => 1,
                                'data-status' => $status + 1,
                                'id' => 'process-btn',
                                'class' => 'h-link-minor',
                            ]
                        )
                    ]);
                case 4:
                    return $this->render('machine', [
                        'openid' => $openid,
                        'service_id' => $model['id'],
                        'mid' => $id,
                        'wid' => $wid,
                        'btnHtml' => Html::a(
                            ConfigBase::getFixMaintainStatus($status),
                            Url::toRoute(['s/affirmfault', 'id' => $wid, 'mid' => $model['id'], 'openid' => $openid]),
                            [
                                'data-ajax' => 0,
                                'data-status' => $status + 1,
                                'id' => 'process-btn',
                                'class' => 'h-link-minor',
                            ]
                        )
                    ]);
                case 5:
                    return $this->render('machine', [
                        'openid' => $openid,
                        'service_id' => $model['id'],
                        'mid' => $id,
                        'wid' => $wid,
                        'btnHtml' =>
                            Html::a(
                                '维修完成',
                                Url::toRoute(['m/processajax', 'id' => $model['id'], 'openid' => $openid]),
                                [
                                    'data-ajax' => 1,
                                    'data-status' => 8,
                                    'class' => 'h-link-minor',
                                    'id' => 'process-btn'
                                ]) .
                            Html::a(
                                ConfigBase::getFixMaintainStatus($status),
                                Url::toRoute(['s/applyparts', 'id' => $model['id'], 'openid' => $openid]),
                                [
                                    'data-ajax' => 0,
                                    'data-status' => $status + 1,
                                    'class' => 'h-link-minor',
                                    'id' => 'process-btn'
                                ])
                    ]);
                case 9:
                    return $this->render('process', [
                        'openid' => $openid,
                        'service_id' => $model['id'],
                        'mid' => $id,
                        'wid' => $wid,
                        'btnHtml' => Html::a(
                            '查看评价',
                            Url::toRoute(['s/showevaluate', 'id' => $model['id']]),
                            [
                                'data-ajax' => 0,
                                'data-status' => $status + 1,
                                'class' => 'h-link-minor',
                                'id' => 'process-btn'
                            ]
                        )
                    ]);
            }
        }

        $status = TblMachineService::find()
            ->select('status')
            ->where(['machine_id' => $id, 'enable' => 'Y'])
            ->andWhere(['<', 'status', 9])
            ->scalar();
        if($status){
            if($status ==8)
                $btnHtml = Html::a('评价维修',Url::toRoute(['s/apply','id'=>$wid,'mid'=>$id]),['class'=>'h-link-minor']);
            else
                $btnHtml = Html::a('维修进度',Url::toRoute(['s/apply','id'=>$wid,'mid'=>$id]),['class'=>'h-link-minor']);
        }else
            $btnHtml = Html::a('申请维修',Url::toRoute(['s/apply','id'=>$wid,'mid'=>$id]),['class'=>'h-link']);

        return $this->render('user',['id'=>$wid,'mid'=>$id,'btnHtml'=>$btnHtml]);

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
