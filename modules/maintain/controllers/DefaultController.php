<?php

namespace app\modules\maintain\controllers;

use app\models\WxBase;
use yii\web\Controller;
use Yii;


class DefaultController extends Controller
{
    public $layout = '/auicss';

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 我的业绩
     */
    public function actionScore($id)
    {
        $openid = WxBase::openId($id);
        $len = Yii::$app->request->get('len')? : 10;

        $model = (new \yii\db\Query())
            ->select('date,fault_time,resp_time,total_km,total_fault,total_score')
            ->from('tbl_analyze_maintain')
            ->where('wx_id=:wid and openid=:openid',[':wid'=>$id,':openid'=>$openid])
            ->limit($len)
            ->orderBy('date desc');
        if(Yii::$app->request->get('startId'))
            $model->andWhere(['<','date',Yii::$app->request->get('startId')]);
        $model = $model->all();


        foreach($model as &$r){
            $r['fault_time'] = number_format($r['fault_time']/3600,2,'.','');
            $r['total_km'] = number_format($r['total_km']/$r['resp_time']*3600,2,'.','');
            $r['total_score'] = number_format($r['total_score']/$r['total_fault'],2,'.','');
        }

        if(Yii::$app->request->get('format') == 'json'){
            return $model? json_encode([
                'status'=>1,
                'data'=>$model,
                'len'=>count($model),
                'startId'=>$model[count($model)-1]['date'],
            ]):json_encode(['status'=>0,'msg'=>'没有数据了','startId'=>0]);
        }

        $startId = $model? $model[count($model)-1]['date']:0;

        if(isset($model[0]['date']) && $model[0]['date'] == date('Ym',time())){
            $lastMonth =  array_shift($model);
        }else{
            $lastMonth = ['fault_time'=>0,'total_km'=>0,'total_score'=>0,'total_fault'=>0];
        }
        return $this->render('score',[
            'model'=>$model,
            'startId'=>$startId,
            'lastMonth'=>$lastMonth,
            'id'=>$id,
        ]);
    }
}
