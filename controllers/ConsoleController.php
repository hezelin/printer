<?php

namespace app\controllers;

use app\models\analyze\TblAnalyzeFault;
use app\models\analyze\TblAnalyzeMachine;
use app\models\analyze\TblAnalyzeMaintain;
use app\models\analyze\TblAnalyzeOrder;
use app\models\analyze\TblAnalyzeProduct;
use app\models\analyze\TblAnalyzeRent;
use app\models\analyze\TblAnalyzeRental;
use app\models\Cache;
use app\models\common\Debug;
use app\models\ConfigBase;
use app\models\TblZujiApply;
use app\models\ToolBase;
use yii\data\ActiveDataProvider;
use Yii;

class ConsoleController extends \yii\web\Controller
{
    public $layout = 'console';
    public $defaultAction = 'view';

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

        $dueTime = (new \yii\db\Query())
            ->select('due_time')
            ->from('tbl_weixin')
            ->where(['id'=>$wx_id])
            ->scalar();
        if($dueTime < time()){
            $this->layout = 'weixin';
            return $this->render('//tips/error',['tips'=>'已过期！']);
        }

        //20161228 biao 维修员表：新增状态字段
        $data['maintainer'] = (new \yii\db\Query())
            ->select(['name','phone','openid','wx_id','wait_repair_count','longitude','latitude','FROM_UNIXTIME(`point_time`,"%Y-%m-%d %H:%i") as point_time'])
            ->from('tbl_user_maintain')
            ->where('wx_id=:wid and status = 10',[':wid'=>$wx_id])
            ->all();

        $data['fault'] = (new \yii\db\Query())
            ->select('t.id,t.content,t.desc,t.type,t.add_time,m.model_name as model,m.brand_name as brand,a.name,a.phone,a.address')
            ->from('tbl_machine_service t')
            ->leftJoin('tbl_machine m','t.machine_id=m.id')
            ->leftJoin('tbl_rent_apply a','a.machine_id=t.machine_id and a.status<11')
            ->where('t.status=1 and t.weixin_id=:wid',[':wid'=>$wx_id])
            ->orderBy('t.add_time desc')//20161222 调整显示顺序
            ->all();
        if($data['fault']){
            foreach($data['fault'] as &$d){
                $tmp = json_decode($d['content'],true);
                $d['cover'] = isset($tmp['cover']['0'])? $tmp['cover'][0]:'/images/call_maintain.png';
            }
        }

        $data['rent'] = (new \yii\db\Query())
            ->select('t.id,t.name,t.phone,t.add_time,u.headimgurl,m.model,m.brand_name,
                p.lowest_expense,p.black_white,p.colours')
            ->from('tbl_rent_apply as t')
            ->where('t.wx_id=:wid and t.status=1',[':wid'=>$wx_id])
            ->orderBy('t.add_time desc')//20161222 调整显示顺序
            ->leftJoin('tbl_user_wechat as u','u.openid=t.openid')
            ->leftJoin('tbl_machine_rent_project as p','p.id=t.project_id')
            ->leftJoin('tbl_machine_model as m','p.machine_model_id=m.id')
            ->all();
//        $data['rent'] = [];
        $data['alert'] = (new \yii\db\Query())
            ->select('expire_count,collect_count')
            ->from('tbl_analyze_rent')
            ->where(['wx_id'=>$wx_id])
            ->orderBy('date_time desc')
            ->limit(1)
            ->one();

        $data['order'] = (new \yii\db\Query())
            ->select('t.order_id,t.order_data,t.remark,t.freight,t.total_price,t.pay_score,t.pay_status,t.order_status,t.add_time,
                d.name,d.phone,d.city,d.address')
            ->from('tbl_shop_order t')
            ->leftJoin('tbl_shop_address d','d.id=t.address_id')
            ->where(['t.enable'=>'Y','t.wx_id'=>$wx_id,'t.order_status'=>[1,5]])
            ->orderBy('t.add_time desc')//20161222 调整显示顺序
            ->all();
        if($data['order']){
            foreach($data['order'] as &$d)
                $d['order_data'] = json_decode($d['order_data'],true);
        }

        $data['part'] = (new \yii\db\Query())
            ->select('t.id,t.status,t.item_id,t.fault_id,p.name,p.market_price,p.price,p.cover,m.content as fault_cover,m.desc,m.type,
                a.name as nickname,a.phone')
            ->from('tbl_parts t')
            ->leftJoin('tbl_product p','p.id=t.item_id')
            ->leftJoin('tbl_machine_service m','m.id=t.fault_id')
            ->leftJoin('tbl_user_maintain a','a.openid=t.openid')
            ->where(['t.status'=>[1,11],'t.wx_id'=>$wx_id,'t.enable'=>'Y'])
            ->orderBy('t.id desc')
            ->all();
        if($data['part']){
            foreach($data['part'] as &$d){
                $tmp = json_decode($d['fault_cover'],true);
                $d['fault_cover'] = isset($tmp['cover']['0'])? $tmp['cover'][0]:'/images/call_maintain.png';
            }
        }

        $data['rental'] = (new \yii\db\Query())
            ->select('t.id,t.machine_id,t.colour,t.black_white,t.total_money,t.exceed_money,t.sign_img,t.name,
                p.name as username,p.address,
                m.model_name as model,m.brand_name as brand')
            ->from('tbl_rent_report t')
            ->leftJoin('tbl_rent_apply p','p.machine_id=t.machine_id and p.status<11')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->where('t.wx_id=:wid and t.status=1',[':wid'=>$wx_id])
            ->orderBy('t.add_time desc')//20161222 调整显示顺序
            ->all();

        return $this->render('view',['data'=>$data,'wx_id'=>$wx_id]);
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

        $rental = new TblAnalyzeRental();

        return $this->render('analyze',[
            'item'=>$item->getCharts(),
//            'stock'=>$item->getItemStock(),
            'machine'=>$machine->getCharts(),
            'charts'=>$ana->getCharts(),
            'rent'=>$rent->getCharts(),
            'order'=>$order->getCharts(),
            'maintainer'=>$maintainer->getCharts(),
            'rental'=> $rental->getRentalCharts()
        ]);
    }

    /*
     * 人人租机报名表
     */
    public function actionZujiApply()
    {
        $this->layout = 'weixin';
        if( Yii::$app->user->isGuest || Yii::$app->user->id != 4 )
            Yii::$app->end('没有权限！');

        $dataProvider = new ActiveDataProvider([
            'query' => TblZujiApply::find(),
            'pagination' => [
                'pageSize' => 15,
            ],
             'sort'=>['defaultOrder'=>['id' => SORT_DESC]]
        ]);

        return $this->render('zujiApply',['dataProvider'=>$dataProvider]);
    }

    /**
     * 20161220 biao 增加
     *
     * 数据轮询，获取最新消息
     *
     */
    public function actionPolling(){

        //$data = [1, 2, 3, 4, 5];

        if(Yii::$app->request->isPost && !empty(Yii::$app->request->post('fromtime'))) {

            $from_time = Yii::$app->request->post('fromtime');//开始获取的时间戳


            //1. 待分配维修
            $data['fault'] = (new \yii\db\Query())
                ->select('t.id,t.content,t.desc,t.type,t.add_time,m.model_name as model,m.brand_name as brand,a.name,a.phone,a.address')
                ->from('tbl_machine_service t')
                ->leftJoin('tbl_machine m', 't.machine_id=m.id')
                ->leftJoin('tbl_rent_apply a', 'a.machine_id=t.machine_id and a.status<11')
                ->where('t.status=1 and t.weixin_id=:wid and t.add_time > :fromtime', [':wid' => Cache::getWid(),':fromtime' => $from_time])//处理时间
                ->orderBy('t.add_time desc')//20161222
                ->all();
            //Debug::log(var_dump($data['fault']));
            if($data['fault']){
                foreach ($data['fault'] as &$d){
                    //var_dump($d);
                    //$d['']
                    $d['type'] =  ConfigBase::getFaultStatus((int)$d['type']);
                    $d['add_time'] = date('m月d日 H:i',$d['add_time']);
                }
            }

            //2. 订单处理
            $data['order'] = (new \yii\db\Query())
                ->select('t.order_id,t.order_data,t.remark,t.freight,t.total_price,t.pay_score,t.pay_status,t.order_status,t.add_time, d.name,d.phone,d.city,d.address')
                ->from('tbl_shop_order t')
                ->leftJoin('tbl_shop_address d','d.id=t.address_id')
                ->where(['t.enable'=>'Y','t.wx_id'=>Cache::getWid(),'t.order_status'=>[1,5]])
                ->andWhere('t.add_time > :fromtime',[':fromtime'=>$from_time])//订单时间处理
                ->orderBy('t.add_time desc')//20161222 调整显示顺序
                ->all();
            if($data['order']){
                foreach($data['order'] as &$d)
                    $d['order_data'] = json_decode($d['order_data'],true);
                    $d['pay_status'] = \app\modules\shop\models\Shop::getPayStatus($d['pay_status']);
                    $d['remark'] = $d['remark'] == ""?"":$d['remark'];
            }

            //3. 配件申请
            $data['part'] = (new \yii\db\Query())
                ->select('t.id,t.status,t.item_id,t.fault_id,p.name,p.market_price,p.price,p.cover,m.content as fault_cover,m.desc,m.type, a.name as nickname,a.phone')
                ->from('tbl_parts t')
                ->leftJoin('tbl_product p','p.id=t.item_id')
                ->leftJoin('tbl_machine_service m','m.id=t.fault_id')
                ->leftJoin('tbl_user_maintain a','a.openid=t.openid')
                ->where(['t.status'=>[1,11],'t.wx_id'=>Cache::getWid(),'t.enable'=>'Y'])
                ->andWhere('t.apply_time > :fromtime', [':fromtime' => $from_time])//时间选择
                ->orderBy('t.id desc')
                ->all();
            if($data['part']){
                foreach($data['part'] as &$d){
                    $tmp = json_decode($d['fault_cover'],true);
                    $d['fault_cover'] = isset($tmp['cover']['0'])? $tmp['cover'][0]:'/images/call_maintain.png';
                    $d['type'] = $d['type'] == ""?"":ConfigBase::getFaultStatus($d['type']);
                    $d['status'] = \app\modules\shop\models\Shop::getParts($d['status']);
                }
            }

            //4. 机器租金
            $data['rental'] = (new \yii\db\Query())
                ->select('t.id,t.machine_id,t.colour,t.black_white,t.total_money,t.exceed_money,t.sign_img,t.name,
                p.name as username,p.address,
                m.model_name as model,m.brand_name as brand')
                ->from('tbl_rent_report t')
                ->leftJoin('tbl_rent_apply p','p.machine_id=t.machine_id and p.status<11')
                ->leftJoin('tbl_machine as m','m.id=t.machine_id')
                ->where('t.wx_id=:wid and t.status=1',[':wid'=>Cache::getWid()])
                ->andWhere('t.add_time > :fromtime', [':fromtime' => $from_time])//时间选择
                ->orderBy('t.add_time desc')//20161222 调整显示顺序
                ->all();

            //5. 租赁申请
            $data['rent'] = (new \yii\db\Query())
                ->select('t.id,t.name,t.phone,t.add_time,u.headimgurl,m.model,m.brand_name,
                p.lowest_expense,p.black_white,p.colours')
                ->from('tbl_rent_apply as t')
                ->where('t.wx_id=:wid and t.status=1 and t.add_time > :fromtime',[':wid'=>Cache::getWid(), ':fromtime' => $from_time])//时间选择
                ->leftJoin('tbl_user_wechat as u','u.openid=t.openid')
                ->leftJoin('tbl_machine_rent_project as p','p.id=t.project_id')
                ->leftJoin('tbl_machine_model as m','p.machine_model_id=m.id')
                ->all();

            return json_encode(['status' => 1,'data' => $data]);
        }//end of isPost
        else{
            return json_encode(['status' => 0, 'data' => 'error']);
        }


    }
}
