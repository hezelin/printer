<?php

namespace app\controllers;


class TestController extends \yii\web\Controller
{
    public function actionCase()
    {
       echo md5('8716162FPIMUDQ4FPIMUDQ4');
    }

    public function actionFive()
    {
        return $this->render('five');
    }

    public function actionFour()
    {
        return $this->render('four');
    }

    public function actionIndex()
    {
        $rand = uniqid( $this->getSalt(7) );
        return $this->render('index',array('rand'=>$rand));
    }

    public function actionOne()
    {
        return $this->render('one');
    }

    public function actionThree()
    {
        return $this->render('three');
    }

    public function actionTwo()
    {
        return $this->render('two');
    }

    public function getSalt($len=8)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        for($i=0,$salt='';$i<$len;$i++)
            $salt.=$str[mt_rand(0,61)];
        return $salt;
    }

}
