<?php

namespace app\controllers;

use app\models\TblMachine;
use app\models\TblMachineRentProject;
use app\models\TblRentApply;
use app\models\ToolBase;
use app\models\WxBase;
use Yii;
use yii\helpers\Url;

class RentController extends \yii\web\Controller
{
    public $layout = 'home';

    public function actionAdd()
    {
        return $this->render('add');
    }

    public function actionDelete()
    {
        return $this->render('delete');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /*
     * 租借机器列表
     * 读取租借方案的数据 tbl_machine_rent_project
     */
    public function actionList($id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id,lowest_expense,p.type,p.cover,p.function,b.name')
            ->from('tbl_machine_rent_project as t')
            ->leftJoin('tbl_machine_model as p','p.id=t.machine_model_id')
            ->leftJoin('tbl_brand as b','b.id=p.brand_id')
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
            ->select('t.id,lowest_expense,p.type,p.cover,p.function,b.name,t.black_white,t.colours,
                t.else_attr as project_attr,p.else_attr,p.cover_images,p.is_color,p.describe
            ')
            ->from('tbl_machine_rent_project as t')
            ->leftJoin('tbl_machine_model as p','p.id=t.machine_model_id')
            ->leftJoin('tbl_brand as b','b.id=p.brand_id')
            ->where(['t.id'=>$project_id])
            ->one();

        $model['cover_images'] = json_decode(str_replace('/s/','/m/',$model['cover_images']),true);
        $model['else_attr'] = array_merge(json_decode($model['else_attr'],true),json_decode($model['project_attr'],true));
        return $this->render('detail',['model'=>$model,'id'=>$id]);
    }

    /*
     * 租借机器详情，这里是租借的内容展示，与租借方案无关
     */
    public function actionMachinedetail($id,$rent_id)
    {
        $model = (new \yii\db\Query())
            ->select('t.id,t.monthly_rent,t.black_white,t.colours,t.add_time,m.series_id,m.else_attr,m.come_from,
                p.type as model,p.cover_images,p.function,p.else_attr as model_attr,p.is_color,p.describe,b.name
            ')
            ->from('tbl_rent_apply as t')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->leftJoin('tbl_machine_model as p','p.id=m.model_id')
            ->leftJoin('tbl_brand as b','b.id=p.brand_id')
            ->where(['t.id'=>$rent_id])
            ->one();

        $model['cover_images'] = json_decode(str_replace('/s/','/m/',$model['cover_images']),true);
        $model['else_attr'] = array_merge(json_decode($model['else_attr'],true),json_decode($model['model_attr'],true));
        return $this->render('machinedetail',['model'=>$model,'id'=>$id]);
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
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->render('//tips/homestatus',[
                'tips'=>'感谢您的申请，我们会在1~2个工作日处理！',
                'btnText'=>'返回',
//                'btnUrl'=>Url::toRoute(['rent/list','id'=>$id])
                'btnUrl'=>'javascript:history.go(-2)'
            ]);
        }else{
            $error = ToolBase::arrayToString( $model->errors );
        }

        return $this->render('apply',['model'=>$model,'error'=>$error]);
    }

    public function actionUpdate()
    {
        return $this->render('update');
    }

    public function actionView()
    {
        return $this->render('view');
    }

}
