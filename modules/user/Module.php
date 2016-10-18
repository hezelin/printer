<?php

namespace app\modules\user;
use Yii;
/**
 * user module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\user\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Yii::$app->errorHandler->errorAction = 'maintain/default/error';        //指定错误

        // custom initialization code goes here
    }
}
