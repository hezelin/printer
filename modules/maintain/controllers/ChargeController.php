<?php

namespace app\modules\maintain\controllers;

use app\models\TblRentApply;
use app\models\TblRentReport;
use app\models\WxBase;
use app\models\WxMedia;
use Yii;
use yii\helpers\Url;

class ChargeController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public $layout = '/home';

    public function actionAdd($id,$machine_id)
    {
        if(Yii::$app->request->post('TblRentReport')){
            $model = new TblRentReport();
            $model->load(Yii::$app->request->post());
            $model->machine_id = $machine_id;
            $model->add_time = time();
            $model->name = (new \yii\db\Query())
                    ->select('name')
                    ->from('tbl_user_maintain')
                    ->where('openid=:openid',[':openid'=>Yii::$app->request->post('openid')])
                    ->scalar();
            $wx = new WxMedia($id);
            $model->sign_img = $wx->getImage( $model->sign_img );

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save();
                $rent = TblRentApply::find()->where('status<11 and machine_id=:mid',[':mid'=>$machine_id])->one();
                $rent->first_rent_time = strtotime( $model->next_rent );
                $rent->save();
                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                return $this->render('//tips/error',[
                    'tips'=>$e,
                    'btnText'=>'返回首页',
                    'btnUrl'=>Url::toRoute(['/wechat/index','id'=>$id])
                ]);
            }
            return $this->render('//tips/home-status',[
                'tips'=>'资料上传成功',
                'btnText'=>'返回主页',
                'btnUrl'=>Url::toRoute(['/wechat/index','id'=>$id]),
                'btnText2'=>'返回修改',
                'btnUrl2'=>url::toRoute(['/maintain/charge/update','id'=>$id,'report_id'=>$model->id])
            ]);
        }
        $openid = WxBase::openId($id);
        $rent = (new \yii\db\Query())
            ->select('monthly_rent,black_white,colours,black_amount,colours_amount,rent_period,name,address, first_rent_time')
            ->from('tbl_rent_apply')
            ->where('machine_id=:mid and status<11',[':mid'=>$machine_id])
            ->orderBy('id desc')
            ->one();
        $lastCharge = (new \yii\db\Query())
            ->select('colour,black_white,total_money,exceed_money,add_time')
            ->from('tbl_rent_report')
            ->where('machine_id=:mid and status<11',[':mid'=>$machine_id])
            ->orderBy('id desc')
            ->one();

        $next_rent = date('Y-m-d',strtotime($rent['rent_period'].' month',$rent['first_rent_time']>1000000000? $rent['first_rent_time']:time()) );

        return $this->render('add',[
            'next_rent'=>$next_rent,
            'wx_id'=>$id,
            'rent'=>$rent,
            'lastCharge'=>$lastCharge,
            'openid'=>$openid
        ]);
    }

    public function actionUpdate($id,$report_id)
    {
        $model = TblRentReport::findOne($report_id);

        if(Yii::$app->request->post('TblRentReport')){
            $model->load(Yii::$app->request->post());
            $model->add_time = time();
            if( strlen($model->sign_img)> 60 ){
                $wx = new WxMedia($id);
                $model->sign_img = $wx->getImage( $model->sign_img );
            }

            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model->save();
                $rent = TblRentApply::find()->where('machine_id=:mid and status<11',[':mid'=>$model->machine_id])->one();
                $rent->first_rent_time = strtotime( $model->next_rent );
                $rent->save();
                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                return $this->render('//tips/error',[
                    'tips'=>$e,
                    'btnText'=>'返回首页',
                    'btnUrl'=>Url::toRoute(['/wechat/index','id'=>$id])
                ]);
            }
            return $this->render('//tips/home-status',[
                'tips'=>'资料上传成功',
                'btnText'=>'返回主页',
                'btnUrl'=>Url::toRoute(['/wechat/index','id'=>$id]),
                'btnText2'=>'返回修改',
                'btnUrl2'=>url::toRoute(['/maintain/charge/update','id'=>$id,'report_id'=>$model->id])
            ]);
        }
        $rent = (new \yii\db\Query())
            ->select('monthly_rent,black_white,colours,black_amount,colours_amount,rent_period,name,address, first_rent_time')
            ->from('tbl_rent_apply')
            ->where('machine_id=:mid and status<11',[':mid'=>$model->machine_id])
            ->orderBy('id desc')
            ->one();
        $lastCharge = (new \yii\db\Query())
            ->select('colour,black_white,total_money,exceed_money,add_time')
            ->from('tbl_rent_report')
            ->where('machine_id=:mid and status<11',[':mid'=>$model->machine_id])
            ->andWhere(['<','id',$model->id])
            ->orderBy('id desc')
            ->one();

        $model->next_rent = date('Y-m-d',$rent['first_rent_time']);
        return $this->render('update',[
            'wx_id'=>$id,
            'rent'=>$rent,
            'lastCharge'=>$lastCharge,
            'model'=>$model
        ]);
    }

}
