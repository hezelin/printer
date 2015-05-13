<?php

namespace app\controllers;

use app\models\DataCity;
use app\models\TblMachineService;
use app\models\WxBase;
use yii\web\BadRequestHttpException;

class MController extends \yii\web\Controller
{
    public $layout = 'home';
    /*
     * 我的评价
     */
    public function actionEvaluate()
    {
        return $this->render('evaluate');
    }

    /*
     * 我的业绩，统计数据
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 最新通知
     */
    public function actionNotice()
    {
        return $this->render('notice');
    }

    public function actionProcess()
    {
        return $this->render('process');
    }

    /*
     * 维修记录
     */
    public function actionRecord()
    {
        return $this->render('record');
    }

    /*
     * 最新任务
     * $id 为公众号id
     */
    public function actionTask($id)
    {
        $openid = WxBase::openId($id);
//        $openid = 'oXMyut1RFKZqchW8qt_6h0OT8FN4';
        $model = (new \yii\db\Query())
            ->select('t.id, t.cover as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,
                    m.address,m.name,m.phone,m.region
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id and m.enable="Y"')
            ->where(['t.openid' => $openid])
            ->andWhere(['<','t.status',10])
            ->all();
        return $this->render('task',['model'=>$model]);
    }

    /*
     * 维修任务详情
     * 维修申请 id
     */
    public function actionTaskdetail($id)
    {
        $model = (new \yii\db\Query())
            ->select('t.cover as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,
                    m.address,m.name,m.phone,m.region
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id and m.enable="Y"')
            ->where(['t.id' => $id])
            ->one();
        if(!$model)
            throw new BadRequestHttpException();
        $region = DataCity::getAddress( $model['region']);
        return $this->render('taskdetail',['model'=>$model,'region'=>$region]);
    }
    /*
     * 提交位置
     */
    public function actionPosition()
    {
        echo '这里获取位置';
    }
}
