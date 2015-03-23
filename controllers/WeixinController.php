<?php

namespace app\controllers;
use Yii;
use app\models\TblWeixin;

class WeixinController extends \yii\web\Controller
{
    public $layout = 'leftmenu';
    public function actionAdd()
    {
        if (Yii::$app->user->isGuest)  $this->redirect(['auth/login','url'=>'/weixin/add']);

        $model = new TblWeixin();
        if($model->load(Yii::$app->request->post()))
        {
            $model->create_time = time();
            $model->due_time = $model->create_time;
            $model->uid = Yii::$app->user->id;
            if($model->save()){
                $this->redirect('index');
            }else{
                print_r($model->errors);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView()
    {
        return $this->render('view');
    }

}
