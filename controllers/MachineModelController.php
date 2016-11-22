<?php

namespace app\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\models\TblMachineModel;


class MachineModelController extends \yii\web\Controller
{
    public $layout = 'console';

    public function actionCreate()
    {
        $model = new TblMachineModel();
        if(Yii::$app->request->post() && $model->load(Yii::$app->request->post()))
        {
            $brand = \app\models\config\ConfigScheme::$brand;
            $model->brand_name = $brand[$model->brand];
            if( $model->save() )
                Yii::$app->session->setFlash('success','添加成功'.$model->model);
            else
                Yii::$app->session->setFlash('error','添加失败');

        }
        return $this->render('create',['model'=>$model]);
    }

}
