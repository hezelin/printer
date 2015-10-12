<?php

namespace app\modules\shop\controllers;

use app\models\Cache;
use app\models\ToolBase;
use Yii;
use yii\helpers\Url;

class CodeController extends \yii\web\Controller
{
    public $layout = '/console';

    /*
     * 配件id,商品 item_id
     */
    public function actionParts($wx_id,$item_id,$un)
    {

        $preDir = "/images/parts/$wx_id/$item_id/";

        $imgUrl = $preDir.$un.'.png';

        if( !is_file(Yii::getAlias('@webroot').$imgUrl) ){
            $url = Url::toRoute(['/shop/codeapi/parts','id' =>$wx_id,'item'=>$item_id,'un'=>$un],'http');
            $dir = ToolBase::newDir($preDir, Yii::getAlias('@webroot'));
            $fileName = $dir . '/' . $un . '.png';
            shell_exec("qrencode -o $fileName '$url' -s 4 -m 2 -l M");
            $imgUrl = $preDir . $un . '.png';
        }

        return $this->render('parts', ['imgUrl' => $imgUrl]);
    }

    public function actionItem($id,$wx_id)
    {
        $index = (int)($id/500);
        $imgUrl = '/images/item/'.(int)($index/500).'/'.$index;
        if( !is_file(Yii::getAlias('@webroot').$imgUrl.'/'.$id.'.png') ){
            $url = Url::toRoute(['/shop/item/detail','id'=>$wx_id,'item_id'=>$id],'http');
            $dir = ToolBase::newDir($imgUrl,Yii::getAlias('@webroot'));
            $fileName = $dir.'/'.$id.'.png';
            shell_exec("qrencode -o $fileName '$url' -s 8 -m 2 -l H");
        }

        return $this->render('item',['qrcodeImgUrl'=>$imgUrl.'/'.$id.'.png']);
    }
}
