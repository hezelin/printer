<?php

namespace app\controllers;

use app\models\Cache;
use app\models\TblRentApply;
use app\models\TblRentReport;
use app\models\TblRentReportSearch;
use app\models\TblRentReportLog;
use Yii;
use yii\helpers\Url;

class ChargeController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionAdd($machine_id)
    {
        $model = new TblRentReport();
        if(Yii::$app->request->post('TblRentReport')){
            $model->load(Yii::$app->request->post());
            $model->machine_id = $machine_id;
            $model->add_time = time();
            $model->name = Yii::$app->user->identity->name;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save();

                $rent = TblRentApply::find()->where('enable="Y" and machine_id=:mid',[':mid'=>$machine_id])->one();
                $rent->first_rent_time = strtotime( $model->next_rent );
                $rent->save();

                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                return $this->render('//tips/error',['tips'=>$e,'btnText'=>'返回租借列表','btnUrl'=>Url::toRoute(['/adminrent/list'])]);
            }
            return $this->redirect('/adminrent/list');
        }

        $rent = (new \yii\db\Query())
            ->select('monthly_rent,black_white,colours,rent_period,name,address, first_rent_time')
            ->from('tbl_rent_apply')
            ->where('machine_id=:mid and enable="Y"',[':mid'=>$machine_id])
            ->orderBy('id desc')
            ->one();
        $lastCharge = (new \yii\db\Query())
            ->select('colour,black_white,total_money,exceed_money,add_time')
            ->from('tbl_rent_report')
            ->where('enable="Y" and machine_id=:mid',[':mid'=>$machine_id])
            ->orderBy('id desc')
            ->one();

        $model->next_rent = strtotime($rent['rent_period'].' month',$rent['first_rent_time']);
        $model->wx_id = Cache::getWid();

        return $this->render('add',['model'=>$model,'rent'=>$rent,'lastCharge'=>$lastCharge,]);
    }

    public function actionDetail()
    {
        return $this->render('detail');
    }

    public function actionList()
    {
        $searchModel = new TblRentReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = TblRentReport::findOne($id);
        if(!$model)
            return '不存在';

        if(Yii::$app->request->post('TblRentReport')){
            $model->load(Yii::$app->request->post());
            $model->name = Yii::$app->request->get('from')? :Yii::$app->user->identity->name;
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save();

                $rent = TblRentApply::find()->where('enable="Y" and machine_id=:mid',[':mid'=>$model->machine_id])->one();
                $rent->first_rent_time = strtotime( $model->next_rent );
                $rent->save();

                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                return $this->render('//tips/error',['tips'=>$e,'btnText'=>'返回租借列表','btnUrl'=>Url::toRoute(['/adminrent/list'])]);
            }
            return $this->redirect('/adminrent/list');
        }
        $rent = (new \yii\db\Query())
            ->select('monthly_rent,black_white,colours,rent_period,name,address, first_rent_time')
            ->from('tbl_rent_apply')
            ->where('machine_id=:mid and enable="Y"',[':mid'=>$model->machine_id])
            ->orderBy('id desc')
            ->one();

        $lastCharge = (new \yii\db\Query())
            ->select('colour,black_white,total_money,exceed_money,add_time')
            ->from('tbl_rent_report')
            ->where('enable="Y" and machine_id=:mid',[':mid'=>$model->machine_id])
            ->andWhere(['<','id',$model->id])
            ->orderBy('id desc')
            ->one();

        $model->next_rent = $rent['first_rent_time'];

        return $this->render('update',['rent'=>$rent,'model'=>$model,'lastCharge'=>$lastCharge]);
    }

    public function actionAffirm()
    {
        return $this->render('affirm');
    }

    public function actionLog($machine_id)
    {
        $searchModel = new TblRentReportLog();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        $rent = (new \yii\db\Query())
            ->select('monthly_rent,black_white,colours,rent_period,t.name,t.address,first_rent_time,
                p.type,m.series_id,b.name as brand')
            ->from('tbl_rent_apply t')
            ->leftJoin('tbl_machine m','t.machine_id=m.id')
            ->leftJoin('tbl_machine_model p','p.id=m.model_id')
            ->leftJoin('tbl_brand b','b.id=p.brand_id')
            ->where('t.machine_id=:mid and t.enable="Y"',[':mid'=>$machine_id])
            ->orderBy('t.id desc')
            ->one();

        return $this->render('log', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rent'=>$rent
        ]);
    }

    public function actionPass()
    {
        if(Yii::$app->request->isAjax){
            $model = TblRentReport::findOne(Yii::$app->request->post('logId'));
            $model->status = 2;
            $model->next_rent = 123;
            if($model->save())
                return json_encode(['status'=>1]);
            else
                return json_encode(['status'=>0,'msg'=>'入库失败']);
        }
        return json_encode(['status'=>1]);
    }

}
