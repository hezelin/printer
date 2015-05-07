<?php

namespace app\controllers;

class MController extends \yii\web\Controller
{
    /*
     * 我的评价
     */
    public function actionEvaluate()
    {
        return $this->render('evaluate');
    }

    /*
     * 我的业绩，统计数据
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 最新通知
     */
    public function actionNotice()
    {
        return $this->render('notice');
    }

    public function actionProcess()
    {
        return $this->render('process');
    }

    /*
     * 维修记录
     */
    public function actionRecord()
    {
        return $this->render('record');
    }

    /*
     * 最新任务
     */
    public function actionTask()
    {
        return $this->render('task');
    }

    /*
     * 提交位置
     */
    public function actionPosition()
    {
        echo '这里获取位置';
    }
}
