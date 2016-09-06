<?php

namespace app\modules\shop;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\shop\controllers';

    public function init()
    {
        parent::init();

        Yii::$app->errorHandler->errorAction = 'maintain/default/error';        //指定错误

        // custom initialization code goes here
    }
}
