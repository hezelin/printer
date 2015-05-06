<?php

namespace app\controllers;

class MController extends \yii\web\Controller
{
    public function actionEvaluate()
    {
        return $this->render('evaluate');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionNotice()
    {
        return $this->render('notice');
    }

    public function actionProcess()
    {
        return $this->render('process');
    }

    public function actionRecord()
    {
        return $this->render('record');
    }

    public function actionTask()
    {
        return $this->render('task');
    }

    /*
     * 微信维修申请
     */
    public function actionApply($id,$mid)
    {
        return $this->render('apply');
    }

}
