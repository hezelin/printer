<?php

namespace app\controllers;
use app\models\TblUserBase;
use Yii;
use app\models\ResetpswdForm;

class UserController extends \yii\web\Controller
{
    public $layout = 'weixin';

    public function actionLog()
    {
        return $this->render('log');
    }

    public function actionReset()
    {
        $model = new ResetpswdForm();
        if($model->load(Yii::$app->request->post()))
        {
            $auth = TblUserBase::findOne(Yii::$app->user->id);
            if( $auth->password === md5($model->oldPassword . $auth->salt . $auth->salt ) ){
                    $auth->password = md5($model->acPassword . $auth->salt . $auth->salt );

                 if( $auth->save() ){
                     return $this->render('//tips/success',['tips'=>'密码修改成功！']);
                 }
                 else
                     Yii::$app->session->setFlash('error',implode("\n",$model->errors));
            }
            else
                $model->addError('oldPassword','密码错误！');
        }
        return $this->render('reset',['model'=>$model]);
    }

}
