<?php

namespace app\modules\user\controllers;
use app\models\TblMachine;
use app\models\TblRentApply;
use app\models\ToolBase;
use app\models\WxBase;
use Yii;
use yii\base\Exception;
use yii\helpers\Url;
use yii\web\HttpException;

class RentController extends \yii\web\Controller
{
    public $layout = '/home';

    /*
     * 租借机器列表
     * 读取租借方案的数据 tbl_machine_rent_project
     */
    public function actionList($id)
    {
        $this->layout = '/auicss';
        $model = (new \yii\db\Query())
            ->select('t.id,t.lowest_expense,t.black_white,t.colours,t.cover,m.model as model_name,m.brand_name')
            ->from('tbl_machine_rent_project as t')
            ->leftJoin('tbl_machine_model as m','m.id=t.machine_model_id')
            ->where(['t.wx_id'=>$id,'t.is_show'=>1])
            ->all();
        return $this->render('list',['model'=>$model,'id'=>$id]);
    }

    /*
     * 租机方案详情，这里主要是租借展示案例
     */
    public function actionDetail($id,$project_id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id,t.lowest_expense,t.describe,m.brand_name,m.model,t.black_white,t.colours,
              t.images,t.contain_paper
            ')
            ->from('tbl_machine_rent_project as t')
            ->leftJoin('tbl_machine_model as m','m.id=t.machine_model_id')
            ->where(['t.id'=>$project_id])
            ->one();

        $model['images'] = json_decode(str_replace('/s/','/m/',$model['images']),true);
        return $this->render('detail',['model'=>$model,'id'=>$id]);
    }

    /*
     * 租借机器详情，这里是租借的内容展示，与租借方案无关
     */
    public function actionMachineDetail($id,$rent_id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id,t.machine_id,t.monthly_rent,t.black_white,t.colours,t.add_time,m.series_id,m.come_from,
                m.brand_name,m.model_name as model,m.images,p.id as project_id,p.describe
            ')
            ->from('tbl_rent_apply as t')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->leftJoin('tbl_machine_rent_project p','m.model_id=p.id')
            ->where(['t.id'=>$rent_id])
            ->one();


        $tmp3 = json_decode(str_replace('/s/','/m/',$model['images']),true);
        $model['images'] = is_array($tmp3)? $tmp3:[];

        return $this->render('machine-detail',['model'=>$model,'id'=>$id]);
    }

    /*
     * 租借机器详情，这里是租借的内容展示，与租借方案无关
     */
    public function actionUserMachine($id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id,t.monthly_rent,t.black_white,t.colours,t.add_time,t.name as user,t.address,
                m.series_id,m.come_from,m.model_name,m.images,m.brand_name
            ')
            ->from('tbl_rent_apply as t')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->where(['t.machine_id'=>$id])
            ->andWhere(['<','t.status',11])
            ->one();

        $tmp3 = json_decode(str_replace('/s/','/m/',$model['images']),true);
        $model['images'] = is_array($tmp3)? $tmp3:[];
        return $this->render('user-machine',['model'=>$model,'id'=>$id]);
    }

    /*
     * 租借申请
     * $id 为公众号id,$mid 为机租借方案 project_id
     */
    public function actionApply($id,$mid)
    {
        $model = new TblRentApply();
        $model->wx_id = $id;
        $model->openid = WxBase::openId($id);
        $model->project_id = $mid;
        $model->add_time = time();
        $model->due_time = time();

        $error = false;
        if ($model->load(Yii::$app->request->post())) {
            if($model->save())
                return $this->render('//tips/home-status',[
                    'tips'=>'感谢您的申请，我们会在1~2个工作日处理！',
                    'btnText'=>'返回',
                    'btnUrl'=>'javascript:history.go(-2)'
                ]);
            else{
                $error = ToolBase::arrayToString( $model->errors );
            }
        }else{
            $data = (new \yii\db\Query())
                ->select('phone,name')
                ->from('tbl_rent_apply')
                ->where(['wx_id'=>$id,'openid'=>$model->openid])
                ->one();
            if($data)
            {
                $model->name = $data['name'];
                $model->phone = $data['phone'];
            }
        }

        return $this->render('apply',['model'=>$model,'error'=>$error]);
    }

    /*
     * 绑定机器
     * $id = 为公众号id
     */
    public function actionBind($id,$machine_id)
    {
        $model = TblRentApply::find()
            ->where(['wx_id'=>$id,'machine_id'=>$machine_id])
            ->andWhere(['<','status',11])
            ->one();
        $model || $model = new TblRentApply();

        $model->openid = WxBase::openId($id);

        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()))
        {
            $model->wx_id = $id;
            $model->project_id = 0;
            $model->machine_id = $machine_id;
            $model->add_time = time();
            $model->due_time = time();
            $model->status = 2;                                     // 已租借状态

            $machine = TblMachine::findOne($machine_id);
            $machine->model_id || $machine->model_id = 1664;                      // -
            $machine->brand || $machine->brand = 'wsz';                        // -
            $machine->images || $machine->images = json_encode(['/img/haoyizu.png']);
            $machine->status = 2;
            $machine->come_from = 2;

            $error = [];
            $transaction= Yii::$app->db->beginTransaction();
            try {
                if(!$model->save())
                   throw new Exception('入库失败');


                if($machine && $machine->wx_id == $id)
                {
                    $machine->status = 2;                           // 机器更改为已租借状态
                    if(!$machine->save())
                        throw new Exception('更改机器状态失败');
                }

                $transaction->commit();
            } catch(\Exception $e) {
                $transaction->rollBack();
                throw new HttpException(401,'数据保存出错==！'.ToolBase::arrayToString($error).$e);
            }

            return $this->render('//tips/home-status',[
                'tips'=>'绑定资料成功！',
                'btnText'=>'申请维修',
                'btnUrl'=>Url::toRoute(['/codeapi/machine','id'=>$machine_id]),
                'jumpUrl'=>Url::toRoute(['/codeapi/machine','id'=>$machine_id]),
            ]);
        }

        return $this->render('bind',['model'=>$model,'wx_id'=>$id]);
    }
}