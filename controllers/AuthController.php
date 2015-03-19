<?php

namespace app\controllers;
use Yii;
use app\models\RegisterForm;
use app\models\LoginForm;
use app\models\ToolBase;
use app\models\DataCity;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;


class AuthController extends \yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

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
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionRegister()
    {
        $model = new RegisterForm();

        if ( $model->load(Yii::$app->request->post()) ) {

            $model->salt = ToolBase::getSalt();
            $model->ip = Yii::$app->request->userIP;
            $model->create_time = time();
            $model->password = md5($model->pswd.$model->salt.$model->salt);

            // 注册成功
            if($model->save()){
                Yii::$app->user->identity = $model;         // 赋值登录
                $this->goHome();
            }else{
                print_r($model->errors);
            }
//            return $this->refresh();
        }

        return $this->render('register',array(
            'model'=>$model,
            'province'=>DataCity::$province,
            'city' => DataCity::$city,
            'region' => DataCity::$region,
        ));
    }

}
