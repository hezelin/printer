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
            'fault_id'=>Yii::$app->request->get('fault_id'),
            'category'=>\app\modules\shop\models\Shop::getMenu($id),
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
            return $this->render('//tips/homestatus',['tips'=>'配件库存为0','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
        $model->amount = $model->amount -1;

        // 非维修员不能申请配件
        if($fault_id = Yii::$app->request->get('fault_id')) {
            $maintain = (new \yii\db\Query())
                ->select('openid')
                ->from('tbl_machine_service')
                ->where('id=:id', [':id' => $fault_id])
                ->scalar();
            if ($openid !== $maintain)
                return $this->render('//tips/homestatus', ['tips' => '没有权限！', 'btnText' => '返回', 'btnUrl' => 'javascript:history.go(-1);']);
            $btnUrl = Url::toRoute(['/m/taskdetail','id'=>$fault_id]);
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

            $parts_id = $parts->id;             // 配件 日志表
            $sql2 = "insert into tbl_parts_log (`parts_id`,`content`,`status`,`add_time`) VALUES ($parts_id,'配件申请',1,$time)";
            $connection->createCommand($sql2)->execute();

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
            echo $e;
            return $this->render('//tips/homestatus',['tips'=>'入库失败','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
        }

        return $this->render('//tips/homestatus',[
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
            ->select('t.wx_id,t.status,t.id as parts_id,p.id,p.cover,p.name,p.price,c.name as category')
            ->from('tbl_parts as t')
            ->leftJoin('tbl_product as p','p.id=t.item_id')
            ->leftJoin('tbl_category as c','c.id=p.category_id')
            ->where('t.fault_id=:fault_id and t.enable="Y"',[':fault_id'=>$fault_id])
            ->all();

        $mUrl = Url::toRoute(['/shop/codeapi/parts'],'http');
        return $this->render('process',['model'=>$model,'id'=>$id,'fault_id'=>$fault_id,'mUrl'=>$mUrl]);
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

            // 配件 日志表
            $sql2 = "insert into tbl_parts_log (`parts_id`,`content`,`status`,`add_time`) VALUES ({$parts->id},'取消配件',1,$time)";
            $connection->createCommand($sql2)->execute();

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

    /*
     * 配件备注
     */
    public function actionRemark($id,$parts_id)
    {
        if(Yii::$app->request->post()){
            $model = new TblPartsLog();
            $model->content = Yii::$app->request->post('content');
            $model->add_time = time();
            $model->status = 13;
            $model->parts_id = $parts_id;
            if(!$model->save())
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));
            else
                return $this->render('//tips/homestatus',[
                   'tips'=>'备注成功',
                    'btnText'=>'返回',
                    'btnUrl'=>'javascript:history.go(-2)'
                ]);
        }
        return $this->render('remark',['parts_id'=>$parts_id,'id'=>$id]);
    }

    /*
     * 配件记录
     */
    public function actionLog($parts_id)
    {
        $model = (new \yii\db\Query())
            ->select('id,content,add_time')
            ->from('tbl_parts_log')
            ->where('parts_id=:parts_id',[':parts_id'=>$parts_id])
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
     * 绑定维修
     */
    public function actionSelect($id,$parts_id)
    {
        $openid = WxBase::openId($id);
        $model = (new \yii\db\Query())
            ->select('t.id, t.cover as fault_cover,t.desc,t.type as fault_type,t.add_time,t.status,
                    m.address,m.name,m.phone
            ')
            ->from('tbl_machine_service as t')
            ->leftJoin('tbl_rent_apply as m','m.machine_id=t.machine_id and m.enable="Y"')
            ->where(['t.openid' => $openid,'t.enable' => 'Y'])
            ->andWhere(['>','unfinished_parts_num',0])
            ->all();

        foreach ($model as $i=>$m) {
            $covers = json_decode($m['fault_cover'],true);
            $model[$i]['fault_cover'] = $covers[0];
        }

        return $this->render('select',['model'=>$model,'wx_id'=>$id,'parts_id'=>$parts_id]);

    }
}
