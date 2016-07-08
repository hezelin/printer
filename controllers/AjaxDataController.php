<?php

namespace app\controllers;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class AjaxDataController extends \yii\web\Controller
{
    public function actionModelType($id)
    {
        $data = (new \yii\db\Query())
            ->select('id,model')
            ->from('tbl_machine_model')
            ->where(['brand'=>$id])
            ->orderBy('model')
            ->all();
        $data = ArrayHelper::map($data,'id','model');

        $html[] = Html::tag('option','选择',['value'=>'']);
        if($data)
            foreach( $data as $k=>$v)
                $html[] = Html::tag('option',$v,['value'=>$k]);

        return implode("\n",$html);
    }

}
