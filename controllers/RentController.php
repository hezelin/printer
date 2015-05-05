<?php

namespace app\controllers;

use app\models\TblMachine;

class RentController extends \yii\web\Controller
{
    public $layout = 'home';

    public function actionAdd()
    {
        return $this->render('add');
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 租借机器列表
     */
    public function actionList($id)
    {
        $model = TblMachine::find()
            ->select('id,brand,type,cover,monthly_rent,function')
            ->where(['wx_id'=>$id,'enable'=>'Y'])
            ->groupBy('type')
            ->asArray()
            ->all();

        return $this->render('list',['model'=>$model]);
    }

    /*
     * 机器详情 detail
     */
    public function actionDetail($id)
    {
        $model = TblMachine::findOne($id);
        return $this->render('detail',['model'=>$model]);
    }

    /*
     * 租借申请
     */
    public function actionApply($id)
    {
        echo 'xxxxxxxxxxxxxxx',$id;
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

    public function actionView()
    {
        return $this->render('view');
    }

}
