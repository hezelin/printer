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
        $rent= (new \yii\db\Query())
            ->select('id,wx_id,project_id')
            ->from('tbl_rent_apply')
            ->where(['machine_id'=>$id,'enable'=>'Y'])
            ->one();
        if(!$rent)
            return $this->render('//tips/homestatus',['tips'=>'租赁用户不存在']);

        $wid = $rent['wx_id'];

        $openid = WxBase::openId($wid);

//        维修员操作
        if(!$this->checkMaintain($openid)) {             // 维修员页面跳转
//            查看维修状态
            $model = TblMachineService::find()
                ->where(['machine_id' => $id, 'openid' => $openid, 'enable' => 'Y'])
                ->andWhere(['<', 'status', 9])
                ->one();
            if (!$model) {                                 // 没有维修申请，机器信息、录入机器坐标
                return $this->render('firstmachine', ['id' => $id, 'wid' => $wid,'rent_id'=>$rent['id'] ]);
            }
            $status = $model['status'];
            switch ($status) {
                case 3:
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
                                'class' => 'process-btn h-link-minor',
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
                            Url::toRoute(['s/affirmfault', 'id' => $wid, 'fault_id' => $model['id'], 'openid' => $openid]),
                            [
                                'data-ajax' => 0,
                                'data-status' => $status + 1,
                                'class' => 'process-btn h-link-minor',
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
                                    'class' => 'process-btn h-link-minor',
                                ]) .
                            Html::a(
                                ConfigBase::getFixMaintainStatus($status),
                                Url::toRoute(['/shop/parts/list', 'id' => $wid, 'fault_id'=>$model['id'],'openid' => $openid]),
                                [
                                    'data-ajax' => 0,
                                    'data-status' => $status + 1,
                                    'class' => 'process-btn h-link-minor',
                                ])
                    ]);
                case 6:
                case 7:
                    return $this->render('machine', [
                        'openid' => $openid,
                        'service_id' => $model['id'],
                        'mid' => $id,
                        'wid' => $wid,
                        'btnHtml' => Html::a(
                            ConfigBase::getFixMaintainStatus($status),
                            Url::toRoute(['/shop/parts/process', 'id'=>$wid, 'fault_id' => $model['id']]),
                            [
                                'data-ajax' => 0,
                                'data-status' => $status + 1,
                                'class' => 'process-btn h-link-minor',
                            ]
                        )
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
                                'class' => 'process-btn h-link-minor',
                            ]
                        )
                    ]);
            }
        }

//        判断用户是否关注
        $isSubscribe = (new \yii\db\Query())
            ->select('count(*)')
            ->from('tbl_user_wechat')
            ->where(['wx_id'=>$wid,'openid'=>$openid])
            ->scalar();
        if(!$isSubscribe){
            $wx_num = (new \yii\db\Query())
                ->select('wx_num')
                ->from('tbl_weixin')
                ->where(['id'=>$wid])
                ->scalar();
            return $this->render('subscribe',['wx_num'=>$wx_num]);
        }
//        用户维修操作
        $data= (new \yii\db\Query())
            ->select('status,id')
            ->from('tbl_machine_service')
            ->where(['machine_id' => $id, 'enable' => 'Y'])
            ->andWhere(['<', 'status', 9])
            ->one();

        if( isset($data['status']) ){
            if( $data['status'] ==8 )
                $btnHtml = Html::a('评价维修',Url::toRoute(['s/evaluate','id'=>$wid,'fault_id'=>$data['id']]),['class'=>'a-no-link h-link-minor']);
            else
                $btnHtml = Html::a('维修进度',Url::toRoute(['s/apply','id'=>$wid,'mid'=>$id]),['class'=>'a-no-link h-link-minor']);
        }else
            $btnHtml = Html::a('申请维修',Url::toRoute(['s/apply','id'=>$wid,'mid'=>$id]),['class'=>'a-no-link h-link']);

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
