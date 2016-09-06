<?php

namespace app\modules\user\controllers;
use app\models\TblMachineService;
use app\models\TblUserWechat;
use app\models\WxBase;

class IController extends \yii\web\Controller
{
    public  $layout = '/auicss';  //使用home布局

    public function actionIndex($id)
    {
        $openid = WxBase::openId($id,false);
        $model = TblUserWechat::find()
            ->select('nickname,headimgurl,province,city')
            ->where(['openid'=>$openid,'wx_id'=>$id])
            ->one();
        return $this->render('index',['model'=>$model,'id'=>$id]);
    }

    /*
     * 我的机器
     * $id 为公众号id
     */
    public function actionMachine($id)
    {
        $openid = WxBase::openId($id);
        $model = (new \yii\db\Query())
            ->select('t.id as rent_id,t.wx_id,t.project_id,t.due_time,t.status,t.monthly_rent,m.id,m.maintain_count,
                m.buy_date,m.cover,m.brand_name,m.model_name,m.series_id,
                s.status as fault_status')
            ->from('tbl_rent_apply as t')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->leftJoin('tbl_machine_service s','s.machine_id=t.machine_id and s.status<9')
            ->where(['t.wx_id' => $id, 't.openid' => $openid,'t.status'=>2])
            ->orderBy('t.id desc')
            ->all();

        $project = (new \yii\db\Query())
            ->select('p.id,p.lowest_expense,p.cover,m.model,m.brand_name')
            ->from('tbl_rent_apply as t')
            ->leftJoin('tbl_machine_rent_project as p','p.id=t.project_id')
            ->leftJoin('tbl_machine_model as m','m.id=p.machine_model_id')
            ->where(['t.wx_id' => $id, 't.openid' => $openid,'t.status'=>1])
            ->all();
//        $project = [];
        return $this->render('machine',['model'=>$model? :[],'project'=>$project? :[],'id'=>$id ]);
    }

    /*
     * 我的积分
     */
    public function actionScore()
    {
        $this->layout = 'home';
        return $this->render('score');
    }

    /*
     * 我的维修
     * $mid 机器 id
     */
    public function actionService($id,$mid)
    {
        $model = TblMachineService::find()->where(['machine_id'=>$mid])->andWhere(['<','status',11])->asArray()->all();
        foreach ($model as $i=>$m) {
            $content = json_decode($m['content'],true);
            $model[$i]['cover'] = $content['cover'][0];
        }
        return $this->render('service',['model'=>$model,'id'=>$id]);
    }

}