<?php

namespace app\modules\shop\controllers;

use app\models\ToolBase;
use Yii;
use yii\helpers\Url;

class CodeController extends \yii\web\Controller
{
    public $layout = '/console';

    /*
     * 获取第三方二维码  url
     */
    private $qrcodeApiUrl = 'http://qr.liantu.com/api.php?';
    /*
     * 配件id,商品 item_id
     */
    public function actionParts($id,$item_id,$wx_id)
    {
        $index = (int)($id/500);
        $imgUrl = '/images/parts/'.(int)($index/500).'/'.$index;
        if( !is_file(Yii::getAlias('@webroot').$imgUrl.'/'.$id.'.jpg') ){
            $urlParams = [
                'text' => Url::toRoute(['codeapi/parts','id'=>$id,'item_id'=>$item_id,'wx_id'=>$wx_id],'http'),
            ];
            $qrcodeImgUrl = $this->qrcodeApiUrl . http_build_query($urlParams);
            $dir = ToolBase::newDir($imgUrl,Yii::getAlias('@webroot'));
            file_put_contents($dir.'/'.$id.'.jpg',file_get_contents($qrcodeImgUrl));
        }

        return $this->render('parts',['qrcodeImgUrl'=>$imgUrl.'/'.$id.'.jpg']);
    }
}
