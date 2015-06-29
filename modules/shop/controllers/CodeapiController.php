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
     * $id 为配件的id
     */
    public function actionParts($id,$wx_id)
    {
        // 绑定维修
        if($fault_id = Yii::$app->request->get('fault_id')){
            $parts = TblParts::findOne($id);
            if(!$parts)
                return $this->render('//tips/homestatus',[
                    'tips'=>'配件未经申请！',
                    'btnText'=>'返回',
                    'btnUrl'=>'javascript:history.go(-1)']);

//            事务操作 绑定配件
            $time = time();
            $connection = Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                if($parts->fault_id){            // 配件状态表删除或更改
                    // 维修表 未完成维修数量减一
                    $sql3 = "update tbl_machine_service set unfinished_parts_num=unfinished_parts_num-1 , parts_arrive_time={$time} where id={$parts->fault_id}";
                    $connection->createCommand($sql3)->execute();
                }else
                    $parts->fault_id = $fault_id;

                // 配件 日志表
                $sql2 = "insert into tbl_parts_log (`parts_id`,`content`,`status`,`add_time`) VALUES ({$parts->id},'配件绑定机器',1,$time)";
                $connection->createCommand($sql2)->execute();

                // 配件表资料完善
                $parts->bing_time = $time;
                $parts->status = 10;
                $parts->save();

                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                echo $e;
                return $this->render('//tips/homestatus',[
                    'tips'=>'系统错误！',
                    'btnText'=>'返回',
                    'btnUrl'=>'javascript:history.go(-1)']);
            }
            return $this->render('//tips/homestatus',[
                'tips'=>'绑定成功！',
                'btnText'=>'返回配件列表',
                'btnUrl'=>Url::toRoute(['/sohp/parts/process','id'=>$wx_id,'fault_id'=>$fault_id])
            ]);
        }

        // 调整到购买页面
        if($item_id = Yii::$app->request->get('item_id')){
            $openid = WxBase::openId($wx_id,false);
            if(!$this->checkMaintain($openid))              // 非维修员跳转到购买页面
                return $this->redirect(['item/detail','id'=>$wx_id,'item_id'=>$item_id]);

            // 维修员处理逻辑
            return $this->render('maintainer',['parts_id'=>$id,'id'=>$wx_id]);

        }

    }


    /*
     * 检查是否维修员
     */
    private function checkMaintain($openid)
    {
        return TblUserMaintain::findOne(['openid'=>$openid])? true:false;
    }

}
