<?php

namespace app\controllers;
use app\models\TblMachineService;
use app\models\TblUserWechat;
use app\models\WxBase;

class IController extends \yii\web\Controller
{
    public  $layout = 'home';  //使用home布局

    public function actionIndex($id)
    {
        $this->layout = 'auicss';
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
            ->select('t.id as rent_id,t.wx_id,t.project_id,t.due_time,t.status,t.monthly_rent,m.id,p.function,m.maintain_count,
                m.else_attr,m.buy_date,p.cover,p.type,m.series_id')
            ->from('tbl_rent_apply as t')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->leftJoin('tbl_machine_model as p','m.model_id=p.id')
            ->where(['t.wx_id' => $id, 't.openid' => $openid,'t.enable'=>'Y','t.status'=>2])
            ->orderBy('t.id desc')
            ->all();

        $project = (new \yii\db\Query())
            ->select('p.id,lowest_expense,m.type,m.cover,m.function,b.name')
            ->from('tbl_rent_apply as t')
            ->leftJoin('tbl_machine_rent_project as p','p.id=t.project_id')
            ->leftJoin('tbl_machine_model as m','m.id=p.machine_model_id')
            ->leftJoin('tbl_brand as b','b.id=m.brand_id')
            ->where(['t.wx_id' => $id, 't.openid' => $openid,'t.enable'=>'Y','t.status'=>1])
            ->all();
        return $this->render('machine',['model'=>$model? :[],'project'=>$project? :[],'id'=>$id ]);
    }

    /*
     * 我的积分
     */
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
        $model = TblMachineService::find()->where(['machine_id'=>$mid,'enable'=>'Y'])->asArray()->all();
        foreach ($model as $i=>$m) {
            $content = json_decode($m['content'],true);
            $model[$i]['cover'] = $content['cover'][0];
        }
        return $this->render('service',['model'=>$model,'id'=>$id]);
    }

}
