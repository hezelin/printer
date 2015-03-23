<?php

namespace app\controllers;
use Yii;

class TestController extends \yii\web\Controller
{
    public function actionCase()
    {
        $file = 'C:\Users\harry\Desktop\html.txt';
//        $remote = 'http://misuosi.com/';
        $remote = $file;
        $data = file_get_contents($remote);
//        echo $data;
//        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        $perl =[
            'css' => '/<link .*?href="(.*?)".*?>/is',
            'img' => '/<img .*?href="(.*?)".*?>/is',
            'js' => '/<script .*?src="(.*?)".*?script>/is',

        ];

        $assets = [];
//        preg_match_all($perl['css'],$data, $assets['css']);
//        preg_match_all($perl['js'],$data, $assets['js']);
        preg_match_all($perl['img'],$data, $assets['img']);
//        echo file_put_contents($file,$data);
//        return $this->render('case');
        echo '<pre>';
        print_r($assets);

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
