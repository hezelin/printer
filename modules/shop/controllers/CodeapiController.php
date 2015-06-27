<?php

namespace app\modules\shop\controllers;

use app\models\TblUserMaintain;
use Yii;

class CodeapiController extends \yii\web\Controller
{

    /*
     * 配件二维码入口
     * $id 为配件的id
     */
    public function actionParts($id,$wx_id)
    {
        if($fault_id = Yii::$app->request->get('fault_id')){
            return Yii::$app->request->url;
        }

        if($item_id = Yii::$app->request->get('item_id')){
            $this->redirect(['item/detail','id'=>$wx_id,'item_id'=>$item_id]);
        }

    }


    /*
     * 检查是否维修员
     */
    private function checkMaintain($openid)
    {
        return TblUserMaintain::findOne(['openid'=>$openid])? false:true;
    }

}
