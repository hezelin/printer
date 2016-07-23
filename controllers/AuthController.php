<?php

namespace app\controllers;
use Yii;
use app\models\RegisterForm;
use app\models\LoginForm;
use app\models\ToolBase;
use app\models\DataCity;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\TblUserBase;
use app\models\ResetpswdForm;


class AuthController extends \yii\web\Controller
{
    public $layout = 'console';

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

    public function actionForgotpswd()
    {
        return $this->render('forgotpswd');
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->gotoBack();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->gotoBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /*
     * 修改密码
     */
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


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->gotoBack();
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
                Yii::$app->getUser()->login( $model->getUser() );
                $this->gotoBack();
            }else{
                print_r($model->errors);
            }
        }

        return $this->render('register',array(
            'model'=>$model,
            'province'=>DataCity::$province,
            'city' => DataCity::$city,
            'region' => DataCity::$region,
        ));
    }

    public function gotoBack()
    {
        if( $url = Yii::$app->request->get('url') ){
            Yii::$app->session->get('url') && Yii::$app->session->remove('url');
            return $this->redirect($url);
        }

        if( $url = Yii::$app->session->get('url') ){
            Yii::$app->session->remove('url');
            return $this->redirect($url);
        }

        return $this->redirect( Yii::$app->getHomeUrl() );
    }

}
