<?php

namespace app\controllers;
use app\models\TblMachineService;
use app\models\TblRentApply;
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
     * $id 为公众号id
     */
    public function actionMachine($id)
    {
        $openid = WxBase::openId($id);
        $model = (new \yii\db\Query())
            ->select('t.wx_id,t.project_id,t.due_time,t.status,t.monthly_rent,m.id,p.function,m.maintain_count,
                m.else_attr,m.buy_date,p.cover,p.type,m.series_id')
            ->from('tbl_rent_apply as t')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->leftJoin('tbl_machine_model as p','m.model_id=p.id')
            ->where(['t.wx_id' => $id, 't.openid' => $openid,'t.enable'=>'Y'])
            ->orderBy('t.status')
            ->all();

        return $this->render('machine',['model'=>$model,'id'=>$id]);
    }

    public function actionScore()
    {
        return $this->render('score');
    }

    /*
     * 我的维修
     * $mid 机器 id
     */
    public function actionService($id,$mid)
    {
        $model = TblMachineService::findAll(['machine_id'=>$mid,'enable'=>'Y']);
        foreach ($model as $i=>$m) {
            $covers = json_decode($m['cover'],true);
            $model[$i]['cover'] = $covers[0];
        }
        return $this->render('service',['model'=>$model]);
    }

}
