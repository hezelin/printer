<?php
/*
 * 维修申请配件
 */
namespace app\modules\shop\controllers;

use app\modules\shop\models\TblParts;
use app\models\WxBase;
use app\modules\shop\models\TblProduct;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class PartsController extends Controller
{
    public $layout = 'shop';
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
            $parts->save();
            $parts_id = $parts->id;
            if(Yii::$app->request->get('fault_id')){
                $sql2 = "insert into tbl_parts_fault (`parts_id`,`fault_id`,`update_time`,`add_time`) VALUES ($parts_id,$fault_id,$time,$time)";
                $sql3 = "update tbl_machine_service set status=6 where id=$fault_id";
                $connection->createCommand($sql3)->execute();
            }
            else
                $sql2 = "insert into tbl_parts_bring (`parts_id`,`openid`,`update_time`,`add_time`) VALUES ($parts_id,'$openid',$time,$time)";

            $connection->createCommand($sql2)->execute();
            $model->save();
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
//            echo $e;
            return $this->render('//tips/homestatus',['tips'=>'入库失败','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
        }
        return $this->render('//tips/homestatus',['tips'=>'配件申请提交成功!','btnText'=>$btnText,'btnUrl'=>$btnUrl]);

    }

    /*
     * 我的配件
     */
    public function actionMy($id)
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
        return $this->render('//tips/homestatus',['tips'=>'随身携带配件列表待开发中...','btnText'=>'返回','btnUrl'=>'javascript:history.go(-1);']);
    }
}
