<?php

namespace app\controllers;

class ShareController extends \yii\web\Controller
{
    public function actionGame()
    {
        return $this->render('game');
    }

    public function actionScore()
    {
        return $this->render('score');
    }

}
