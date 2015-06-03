<?php

namespace app\controllers;

class ShareController extends \yii\web\Controller
{
    public $layout = 'home';
    public function actionActive()
    {
        return $this->render('active');
    }

    public function actionGame()
    {
        return $this->render('game');
    }

    public function actionScore($id)
    {
        return $this->render('score',['id'=>$id]);
    }

    /*
     * 分享方案
     */
    public function actionScheme($id)
    {
        return $this->render('scheme',['id'=>$id]);
    }

}
