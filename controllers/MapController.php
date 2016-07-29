<?php

namespace app\controllers;

class MapController extends \yii\web\Controller
{
    public $layout = 'console';

    /*
     * 租赁用户地图显示
     */
    public function actionRent()
    {
        $model = (new \yii\db\Query())
            ->select('name,address,latitude,longitude,machine_id,monthly_rent,black_white,colours,due_time,first_rent_time,rent_period,apply_word')
            ->from('tbl_rent_apply')
            ->where(['status'=>[2,3]])
            ->all();

        $points = [];
        foreach($model as $m)
            if($m['latitude'] && $m['longitude'])
            $points[] = [$m['latitude'],$m['longitude']];

        return $this->render('rent',['model'=>$model,'points'=>$points]);
    }

}
