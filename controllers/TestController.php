<?php

namespace app\controllers;


class TestController extends \yii\web\Controller
{
    public function actionCase()
    {
        $str = "'./configure' '--prefix=/alidata/server/php' '--enable-opcache' '--with-config-file-path=/alidata/server/php/etc' '--with-mysql=mysqlnd' '--with-mysqli=mysqlnd' '--with-pdo-mysql=mysqlnd' '--enable-fpm' '--enable-fastcgi' '--enable-static' '--enable-inline-optimization' '--enable-sockets' '--enable-wddx' '--enable-zip' '--enable-calendar' '--enable-bcmath' '--enable-soap' '--with-zlib' '--with-iconv' '--with-gd' '--with-xmlrpc' '--enable-mbstring' '--without-sqlite' '--with-curl' '--enable-ftp' '--with-mcrypt' '--with-freetype-dir=/usr/local/freetype.2.1.10' '--with-jpeg-dir=/usr/local/jpeg.6' '--with-png-dir=/usr/local/libpng.1.2.50' '--disable-ipv6' '--disable-debug' '--with-openssl' '--disable-maintainer-zts' '--disable-safe-mode' '--disable-fileinfo'";
        $str = str_replace('\'','',$str);

        echo '<pre>';
        echo $str;
//        return $this->render('case');
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
