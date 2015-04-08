<?php

namespace app\controllers;

use app\models\TblMachine;
use yii\helpers\Url;

class CodeController extends \yii\web\Controller
{
    public $qrcodeApiUrl = 'http://qr.liantu.com/api.php?';

    public $layout = 'console';

    public function actionBinding()
    {
        return $this->render('binding');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 生成机器码
     */
    public function actionMachine($id)
    {
        $model = TblMachine::findOne($id);
        $urlParams = [
            'text' => Url::toRoute(['codeapi/machine','id'=>$id],'http'),
        ];

        $qrcodeImgUrl = $this->qrcodeApiUrl . http_build_query($urlParams);


        return $this->render('machine',['model'=>$model,'qrcodeImgUrl'=>$qrcodeImgUrl]);
    }

    public function actionScore()
    {
        return $this->render('score');
    }

}
