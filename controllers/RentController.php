<?php

namespace app\controllers;

use app\models\TblMachine;
use app\models\TblRentApply;
use app\models\ToolBase;
use app\models\WxBase;
use Yii;
use yii\helpers\Url;

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
            ->where(['wx_id'=>$id,'enable'=>'Y','status'=>1])
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
     * $id 为公众号id,$mid 为机器id
     */
    public function actionApply($id,$mid)
    {
        $model = new TblRentApply();
        $model->wx_id = $id;
        $model->openid = WxBase::openId($id);
        $model->machine_id = $mid;
        $model->add_time = time();
        $model->due_time = time();

        $error = false;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            $this->refresh();
            return $this->render('//tips/homestatus',[
                'tips'=>'感谢您的申请，我们会在1~2个工作日跟你联系！',
                'btnText'=>'返回',
//                'btnUrl'=>Url::toRoute(['rent/list','id'=>$id])
                'btnUrl'=>'javascript:history.go(-2)'
            ]);
        }else{
            $error = ToolBase::arrayToString( $model->errors );
        }

        return $this->render('apply',['model'=>$model,'error'=>$error]);
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
