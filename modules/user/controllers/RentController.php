<?php

namespace app\modules\user\controllers;
use app\models\TblRentApply;
use app\models\ToolBase;
use app\models\WxBase;
use Yii;

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
              t.images
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
            ->select('t.id,t.monthly_rent,t.black_white,t.colours,t.add_time,m.series_id,m.come_from,
                m.brand_name,m.model_name as model,m.images,p.describe
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
                m.series_id,m.else_attr,m.come_from,
                p.type as model,p.cover_images,p.function,p.else_attr as model_attr,p.is_color,p.describe,b.name
            ')
            ->from('tbl_rent_apply as t')
            ->leftJoin('tbl_machine as m','m.id=t.machine_id')
            ->leftJoin('tbl_machine_model as p','p.id=m.model_id')
            ->leftJoin('tbl_brand as b','b.id=p.brand_id')
            ->where(['t.machine_id'=>$id,'t.enable'=>'Y'])
            ->one();


        $tmp1 = json_decode($model['else_attr'],true);
        $tmp2 = json_decode($model['model_attr'],true);
        $tmp3 = json_decode(str_replace('/s/','/m/',$model['cover_images']),true);
        $model['cover_images'] = is_array($tmp3)? $tmp3:[];
        $model['else_attr'] = array_merge(is_array($tmp1)?$tmp1:[],is_array($tmp2)?$tmp2:[]);

        return $this->render('userMachine',['model'=>$model,'id'=>$id]);
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
//                'btnUrl'=>url::toRoute(['/user/rent/list','id'=>$id])
                'btnUrl'=>'javascript:history.go(-2)'
            ]);
        }else{
            $error = ToolBase::arrayToString( $model->errors );
        }

        return $this->render('apply',['model'=>$model,'error'=>$error]);
    }
}