<?php

namespace app\controllers;

class TipsController extends \yii\web\Controller
{
    public $layout = '/home';
    public function actionError()
    {
        $data = [
            'tips'=>'状态样式调整',
            'btnText'=>'链接一',
            'btnUrl'=>'#',
        ];
        return $this->render('error',$data);
    }

    public function actionIndex()
    {
        $data = [
            'tips'=>'状态样式调整',
            'btnText'=>'链接一',
            'btnUrl'=>'#',
        ];
        return $this->render('home-status',$data);
    }

    public function actionSuccess()
    {
        $data = [
            'tips'=>'状态样式调整',
            'btnText'=>'链接一',
            'btnUrl'=>'#',
        ];
        return $this->render('success',$data);
    }

}
