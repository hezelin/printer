<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeFault;
use app\models\analyze\TblAnalyzeMachine;
use app\models\analyze\TblAnalyzeMaintain;
use app\models\analyze\TblAnalyzeOrder;
use app\models\analyze\TblAnalyzeProduct;
use app\models\analyze\TblAnalyzeRent;
use app\models\Cache;
use app\models\TblWeixin;
use yii\web\NotFoundHttpException;
use Yii;

class ConsoleController extends \yii\web\Controller
{
    public $layout = 'console';

    /*
     * 工作报表
     * $model = TblWeixin::find()
            ->where(['id'=>$id,'enable'=>'Y'])
            ->asArray()
            ->one();
        Yii::$app->session['wechat'] = $model;
     */
    public function actionView()
    {
        $wx_id = Cache::getWid();

        $data['maintainer'] = (new \yii\db\Query())
            ->select('name,wait_repair_count')
            ->from('tbl_user_maintain')
            ->where('wx_id=:wid',[':wid'=>$wx_id])
            ->all();

        $data['fault'] = (new \yii\db\Query())
            ->select('t.cover,t.desc,t.type,t.add_time,p.type as model,b.name as brand,a.name,a.phone,a.address')
            ->from('tbl_machine_service t')
            ->leftJoin('tbl_machine m','t.machine_id=m.id')
            ->leftJoin('tbl_machine_model p','p.id=m.model_id')
            ->leftJoin('tbl_brand b','b.id=p.brand_id')
            ->leftJoin('tbl_rent_apply a','a.machine_id=t.machine_id and a.enable="Y"')
            ->where('t.enable="Y" and t.status=1 and t.weixin_id=:wid',[':wid'=>$wx_id])
            ->all();
        if($data['fault']){
            foreach($data['fault'] as &$d){
                $tmp = json_decode($d['cover'],true);
                $d['cover'] = $tmp['0'];
            }
        }

        /*$data['rent'] = (new \yii\db\Query())
            ->select('t.id,t.name,t.phone,t.add_time,u.nickname,u.headimgurl,u.sex,m.type,m.cover_images,m.is_color')
            ->from('tbl_rent_apply as t')
            ->where('t.wx_id=:wid and t.status=1 and t.enable="Y"',[':wid'=>$wx_id])
            ->leftJoin('tbl_user_wechat as u','u.openid=t.openid')
            ->leftJoin('tbl_machine_model as m','p.machine_model_id=m.id')
            ->all();*/

        return $this->render('view',['data'=>$data]);
    }

    /*
     * 数据统计
     */
    public function actionAnalyze()
    {
        $ana = new TblAnalyzeFault();

        $rent= new TblAnalyzeRent();

        $order = new TblAnalyzeOrder();

        $maintainer = new TblAnalyzeMaintain();

        $machine = new TblAnalyzeMachine();

        $item = new TblAnalyzeProduct();
        return $this->render('analyze',[
            'item'=>$item->getCharts(),
            'stock'=>$item->getItemStock(),
            'machine'=>$machine->getCharts(),
            'charts'=>$ana->getCharts(),
            'rent'=>$rent->getCharts(),
            'order'=>$order->getCharts(),
            'maintainer'=>$maintainer->getCharts()
        ]);
    }
}
