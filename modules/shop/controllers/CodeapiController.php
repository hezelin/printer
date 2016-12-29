<?php

namespace app\modules\shop\controllers;

use app\models\TblUserMaintain;
use app\models\WxBase;
use app\modules\shop\models\TblParts;
use Yii;
use yii\helpers\Url;

class CodeapiController extends \yii\web\Controller
{
    public $layout = 'shop';
    /*
     * 配件二维码入口
     * id 公众号id / item 类目id / un  配件uniqid
     */
    public function actionParts($id,$item,$un)
    {
        // 调转到购买页面
        $openid = WxBase::openId($id,false);
        if(!$this->checkMaintain($openid))              // 非维修员跳转到购买页面
            return $this->redirect(['item/detail','id'=>$id,'item_id'=>$item]);

        $part = (new \yii\db\Query())
            ->select('id,machine_id,status')
            ->from('tbl_parts')
            ->where(['un'=>$un,'enable'=>'Y'])
            ->one();

        if(!$part) $part = ['machine_id'=>'','id'=>'','status'=>''];
        // 维修员处理逻辑
        return $this->render('maintainer',[
            'un'=>$un,
            'id'=>$id,
            'item_id'=>$item,
            'openid'=>$openid,
            'part'=>$part,
        ]);
    }


    /*
     * 检查是否维修员
     */
    private function checkMaintain($openid)
    {
        //20161228 biao 维修员表：新增状态表
        return TblUserMaintain::findOne(['openid'=>$openid, 'status' => 10])? true:false;
    }

}
