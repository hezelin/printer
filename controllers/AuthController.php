<?php

namespace app\controllers;
use Yii;
use app\models\RegisterForm;
use app\models\ToolBase;

class AuthController extends \yii\web\Controller
{

    public function actionCheck()
    {
        return $this->render('check');
    }

    public function actionForgotpswd()
    {
        return $this->render('forgotpswd');
    }

    public function actionLogin()
    {
        return $this->render('login');
    }

    public function actionLogout()
    {
        return $this->render('logout');
    }

    public function actionRegister()
    {
        $model = new RegisterForm();

        if ( $model->load(Yii::$app->request->post()) ) {

            $model->salt = ToolBase::getSalt();
            $model->ip = Yii::$app->request->userIP;
            $model->create_time = time();
            $model->password = md5($model->pswd.$model->salt.$model->salt);

            if(!$model->save()){
                print_r($model->errors);
            }

            Yii::$app->session->setFlash('contactFormSubmitted');

//            return $this->refresh();
        }

        return $this->render('register',array('model'=>$model));
    }

}
