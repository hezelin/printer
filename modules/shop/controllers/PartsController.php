<?php
/*
 * 维修申请配件
 */
namespace app\modules\shop\controllers;

use app\models\TblMachineService;
use app\models\ToolBase;
use app\modules\shop\models\TblParts;
use app\models\WxBase;
use app\modules\shop\models\TblPartsLog;
use app\modules\shop\models\TblProduct;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class PartsController extends Controller
{
    public $layout = 'shop';
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 查询产品列表
     */
    public function actionList($id)
    {
        $this->layout = '/auicss';
        $len = Yii::$app->request->get('len')? : 10;

        $model = (new \yii\db\Query())
            ->select('t.id,t.wx_id,c.name as category,t.name,t.cover,t.price')
            ->from('tbl_product as t')
            ->leftJoin('tbl_category as c','c.id=t.category_id')
            ->where('t.enable="Y" and t.wx_id=:wid',[':wid'=>$id])
            ->limit($len)
            ->orderBy('t.id desc');
        if(Yii::$app->request->get('startId'))
            $model->andWhere(['<','t.id',Yii::$app->request->get('startId')]);
        if(Yii::$app->request->get('q'))
            $model->andWhere(['like','t.name',Yii::$app->request->get('q')]);
        if(Yii::$app->request->get('key') && Yii::$app->request->get('key') != 'all')
            $model->andWhere('t.category_id=:cate',[':cate'=>Yii::$app->request->get('key')]);

        $model = $model->all();

        if(Yii::$app->request->get('format') == 'json'){
            return $model? json_encode([
                'status'=>1,
                'data'=>$model,
                'len'=>count($model),
                'startId'=>$model[count($model)-1]['id'],
            ]):json_encode(['status'=>0,'msg'=>'没有数据了','startId'=>0]);
        }

        $startId = $model? $model[count($model)-1]['id']:0;

        return $this->render('list',[
            'model'=>$model,
            'startId'=>$startId,
            'id'=>$id,
            'len'=>$len,
            'fault_id'=>Yii::$app->request->get('fault_id'),
        ]);
    }

    /*
     * 详情
     */
    public function actionDetail($id,$item_id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id,t.wx_id,c.name as category,t.name,t.cover_images,t.price,t.market_price,t.describe,t.add_attr')
            ->from('tbl_product as t')
            ->leftJoin('tbl_category as c','c.id=t.category_id')
            ->where('t.enable="Y" and t.id=:id',[':id'=>$item_id])
            ->one();
        $model['cover_images'] = json_decode(str_replace('/s/','/m/',$model['cover_images']),true);
        $model['big_cover_images'] = [];
        foreach($model['cover_images'] as $img){
            $model['big_cover_images'][] = Yii::$app->request->hostInfo.str_replace('/m/','/o/',$img);
        }
        $model['else_attr'] = json_decode($model['add_attr'],true);

        return $this->render('detail',['model'=>$model,'id'=>$id]);
    }

    /*
     * 配件申请
     * $fault_id 可省略，如果存在就绑定维修 tbl_parts_faults ，不存在就绑定到携带表 tbl_parts_bring
     */
    public function actionApply($id,$item_id)
    {
        $openid = WxBase::openId($id);

        // 查询商品库 库存
        $model = TblProduct::findOne($item_id);
        if($model->amount <= 0)
            return $this->render('//tips/home-status',['tips'=>'配件库存为0','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
        $model->amount = $model->amount -1;

        // 非维修员不能申请配件
        if($fault_id = Yii::$app->request->get('fault_id')) {

            $isMaintainer = (new \yii\db\Query())
                ->select('count(*)')
                ->from('tbl_user_maintain')
                ->where('openid=:openid', [':openid' => $openid])
                ->scalar();
            if ( !$isMaintainer )
                return $this->render('//tips/home-status', ['tips' => '不是维修员，没有权限！', 'btnText' => '返回', 'btnUrl' => 'javascript:history.go(-1);']);

            $btnUrl = Url::toRoute(['/maintain/task/detail','id'=>$fault_id]);
            $btnText = '返回维修';
        }else{
            $btnText = '返回主页';
            $btnUrl = Url::toRoute(['/wechat/index','id'=>$id]);
        }


        $time = time();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {
            $parts = new TblParts();
            $parts->wx_id = $id;
            $parts->item_id = $item_id;
            $parts->openid = $openid;
            $parts->apply_time = $time;
            if(Yii::$app->request->get('fault_id'))
                $parts->fault_id = $fault_id;
            $parts->save();                     // 配件表 保存

            $model->save();                     // 商品库存 减 一

            if(Yii::$app->request->get('fault_id')){    // 更新维修表
                $machine = TblMachineService::findOne($fault_id);
                $machine->status = 6;
                if( !$machine->parts_apply_time)
                    $machine->parts_apply_time = $time;
                $machine->unfinished_parts_num = $machine->unfinished_parts_num + 1;
                $machine->save();
            }
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
//            echo $e;
            return $this->render('//tips/home-status',['tips'=>'入库失败','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
        }

        return $this->render('//tips/home-status',[
            'tips'=>'配件申请提交成功!',
            'btnText'=>$btnText,
            'btnUrl'=>$btnUrl,
            'btnText2'=>'继续申请配件',
            'btnUrl2'=>Url::toRoute(['/shop/parts/list','id'=>$id,'fault_id'=>Yii::$app->request->get('fault_id')])
        ]);

    }

    /*
     * 我的配件
     */
    public function actionMy($id)
    {
        $this->layout = '/auicss';
        $openid = WxBase::openId($id);
        $model = (new \yii\db\Query())
            ->select('t.wx_id,t.status,t.id as parts_id,p.id,p.cover,p.name,p.price,c.name as category')
            ->from('tbl_parts as t')
            ->leftJoin('tbl_product as p','p.id=t.item_id')
            ->leftJoin('tbl_category as c','c.id=p.category_id')
            ->where('t.openid=:openid and t.enable="Y"',[':openid'=>$openid])
            ->andWhere(['<','t.status',10])
            ->all();

        return $this->render('my',['model'=>$model,'id'=>$id]);
    }

    /*
     * 维修配件进度
     */
    public function actionProcess($id,$fault_id)
    {
        $model = (new \yii\db\Query())
            ->select('t.wx_id,t.status,t.id as parts_id,t.item_id,p.id,p.cover,p.name,p.price,c.name as category')
            ->from('tbl_parts as t')
            ->leftJoin('tbl_product as p','p.id=t.item_id')
            ->leftJoin('tbl_category as c','c.id=p.category_id')
            ->where('t.fault_id=:fault_id and t.enable="Y"',[':fault_id'=>$fault_id])
            ->andWhere(['<','t.status',10])
            ->all();

        return $this->render('process',['model'=>$model,'id'=>$id,'fault_id'=>$fault_id]);
    }


    /*
     * 选择绑定
     */
    public function actionSelect($id,$un,$item_id)
    {
        $model = (new \yii\db\Query())
            ->select('t.wx_id,t.status,t.id as parts_id,t.item_id,t.fault_id,p.id,p.cover,p.name,p.price,c.name as category,m.machine_id')
            ->from('tbl_parts as t')
            ->leftJoin('tbl_product as p','p.id=t.item_id')
            ->leftJoin('tbl_category as c','c.id=p.category_id')
            ->leftJoin('tbl_machine_service as m','m.id=t.fault_id and t.fault_id>0')
            ->where('t.enable="Y" and t.item_id=:item_id and t.wx_id=:wx_id and t.openid=:openid',[':item_id'=>$item_id,':wx_id'=>$id,':openid'=>Yii::$app->request->get('openid')])
            ->andWhere(['<','t.status',10])
            ->all();

        /*echo '<pre>';
        print_r($model);
        exit;*/
        return $this->render('select',['model'=>$model,'id'=>$id,'un'=>$un,'item_id'=>$item_id]);
    }

    /*
     * 绑定配件
     * id 微信id，un uniqid ，item_id
     */
    public function actionBing($id,$un,$item_id)
    {
        $openid = WxBase::openId($id);
        if($part_id = Yii::$app->request->get('part_id') ){
            $model = TblParts::findOne($part_id);
            if(Yii::$app->request->get('fault_id')){
                $model->machine_id = (new \yii\db\Query())
                    ->select('machine_id')
                    ->from('tbl_machine_service')
                    ->where(['id'=>$model->fault_id])
                    ->scalar();
                $content['fault_id'] = $model->fault_id;
            }
        }else if($machine_id = Yii::$app->request->get('machine_id')){
            $model = TblParts::findOne(['un'=>$un,'item_id'=>$item_id,'wx_id'=>$id,'enable'=>'Y']);
            if(!$model) $model = new TblParts();
            $model->machine_id = $machine_id;
        }else
            return $this->render('//tips/home-status',[
                'tips'=>'数据不合法！',
                'btnText'=>'返回',
                'btnUrl'=>'javascript:history.go(-1)']);

        $model->un = $un;
        $model->wx_id = $id;
        $model->item_id = $item_id;
        $model->openid = $openid;
        $model->status = 10;
        $model->apply_time = $model->bing_time = time();
        $model->fault_id = Yii::$app->request->get('fault_id')? : 0;

        $name = (new \yii\db\Query())
            ->select('name')
            ->from('tbl_user_maintain')
            ->where(['wx_id'=>$id,'openid'=>$openid])
            ->scalar();

        $content['text'] = "$name 绑定机器";
        $content['machine_id'] = $model->machine_id;
        $content = json_encode($content,JSON_UNESCAPED_UNICODE);

        //            事务操作 绑定配件
        $time = time();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $model->save();                  // 配件基本资料

            if($model->fault_id){            // 配件状态表删除或更改
                // 维修表 未完成维修数量减一
                $sql3 = "update tbl_machine_service set unfinished_parts_num=unfinished_parts_num-1 , parts_arrive_time={$time} where id={$model->fault_id}";
                $connection->createCommand($sql3)->execute();
            }

            // 配件 日志表
            $sql2 = "insert into tbl_parts_log (`un`,`wx_id`,`item_id`,`content`,`status`,`add_time`) VALUES ('{$un}',{$id},{$item_id},'{$content}',10,$time)";
            $connection->createCommand($sql2)->execute();

            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            echo $e;
            return $this->render('//tips/home-status',[
                'tips'=>'系统错误！',
                'btnText'=>'返回',
                'btnUrl'=>'javascript:history.go(-1)']);
        }

        return $this->render('//tips/home-status',[
            'tips'=>'绑定成功！',
            'btnText'=>'返回首页',
            'btnUrl'=>Url::toRoute(['/wechat/index','id'=>$id])]);
    }

    /*
     * 绑定配件
     * id 微信id，un uniqid ，item_id
     */
    public function actionUnbing($id,$un,$item_id,$part_id,$machine_id,$openid)
    {
        $model = TblParts::findOne($part_id);
        $model->status = 11;

        $name = (new \yii\db\Query())
            ->select('name')
            ->from('tbl_user_maintain')
            ->where(['wx_id'=>$id,'openid'=>$openid])
            ->scalar();

        $content['text'] = "$name 解除机器";
        $content['machine_id'] = $model->machine_id;
        $content = json_encode($content,JSON_UNESCAPED_UNICODE);

        //            事务操作 绑定配件
        $time = time();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $model->save();                  // 解除绑定，更改状态

            // 配件 日志表
            $sql2 = "insert into tbl_parts_log (`un`,`wx_id`,`item_id`,`content`,`status`,`add_time`) VALUES ('{$un}',{$id},{$item_id},'{$content}',11,$time)";
            $connection->createCommand($sql2)->execute();

            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            echo $e;
            return $this->render('//tips/home-status',[
                'tips'=>'系统错误！',
                'btnText'=>'返回',
                'btnUrl'=>'javascript:history.go(-1)']);
        }

        return $this->render('//tips/home-status',[
            'tips'=>'解除绑定成功！',
            'btnText'=>'返回首页',
            'btnUrl'=>Url::toRoute(['/wechat/index','id'=>$id])]);
    }

    /*
     * 配件记录
     */
    public function actionLog($un,$id,$item_id)
    {
        $model = (new \yii\db\Query())
            ->select('id,content,add_time')
            ->from('tbl_parts_log')
            ->where('un=:un and wx_id=:id and item_id=:item_id',[':un'=>$un,':id'=>$id,':item_id'=>$item_id])
            ->limit(10)
            ->orderBy('id desc');
        if(Yii::$app->request->get('startId'))
            $model->andWhere(['<','id',Yii::$app->request->get('startId')]);

        $model = $model->all();
        if(Yii::$app->request->get('format') == 'json'){
            return $model? json_encode([
                'status'=>1,
                'data'=>$model,
                'len'=>count($model),
                'startId'=>$model[count($model)-1]['id'],
            ]):json_encode(['status'=>0,'msg'=>'没有数据了','startId'=>0]);
        }

        $startId = $model? $model[count($model)-1]['id']:0;
        return $this->render('log', [
            'model'=>$model,
            'startId'=>$startId
        ]);
    }

    /*
     * 配件备注
     */
    public function actionRemark($id,$un,$item_id)
    {
        if(Yii::$app->request->post()){
            $model = new TblPartsLog();
            if(!$model->remark())
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
            else
                return $this->render('//tips/home-status',[
                    'tips'=>'备注成功',
                    'btnText'=>'返回',
                    'btnUrl'=>'javascript:history.go(-2)'
                ]);
        }
        return $this->render('remark',['un'=>$un,'id'=>$id]);
    }

    /*
     * 取消配件
     * 事务操作
     * 1、写入配件日志
     * 2、更改维修表 未完成配件数量
     * 3、更改配件的状态或者删除
     * 4、更新商品库存
     */
    public function actionCancel($id,$parts_id)
    {
        $parts = TblParts::findOne($parts_id);
        if(!$parts) Yii::$app->end(json_encode(['status'=>1]));

        $time = time();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {
            if($parts->status == 1){            // 配件状态表删除或更改
                $parts->delete();
            }else{
                $parts->status = 11;
                $parts->save();
            }

            // 商品库存 加一
            $sql3 = "update tbl_product set amount=amount+1 where id={$parts->item_id}";
            $connection->createCommand($sql3)->execute();

            if($parts->fault_id){
                // 维修表 未完成维修数量减一
                $sql3 = "update tbl_machine_service set unfinished_parts_num=unfinished_parts_num-1 , parts_arrive_time={$time} where id={$parts->fault_id}";
                $connection->createCommand($sql3)->execute();
            }
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            echo $e;
            Yii::$app->end(json_encode(['status'=>0,'msg'=>'入库失败']));
        }

        echo json_encode(['status'=>1]);
    }
}
