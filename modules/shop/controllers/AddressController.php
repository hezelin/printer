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
use app\modules\shop\models\TblShopAddress;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class AddressController extends Controller
{
    public $layout = 'shop';
    public $enableCsrfValidation = false;

    /*
     * 添加地址
     */
    public function actionAdd($id)
    {
        if(Yii::$app->request->post())
        {
            $model = new TblShopAddress();
            $model->load(Yii::$app->request->post());
            $model->wx_id = $id;
            $model->add_time = time();
            if($model->save())
                return Yii::$app->request->get('url')? $this->redirect( Yii::$app->request->get('url') ):$this->redirect('/wechat/index/'.$id);
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));

        }
        $openid = WxBase::openId($id);
        return $this->render('add',['id'=>$id,'openid'=>$openid]);
    }

    /*
     * 我的地址
     */
    public function actionList($id)
    {
        $openid = WxBase::openId($id);

        $model = (new \yii\db\Query())
            ->select('id,name,phone,city,address')
            ->from('tbl_shop_address')
            ->where('openid=:openid',[':openid'=>$openid])
            ->orderBy('add_time desc')
            ->all();
        return $this->render('list',['model'=>$model,'id'=>$id]);
    }

    /*
     * 修改地址
     */
    public function actionUpdate($id,$address_id)
    {
        $model = TblShopAddress::findOne($address_id);
        if(Yii::$app->request->post())
        {
            $model->load(Yii::$app->request->post());
            $model->add_time = time();
            if($model->save())
                return Yii::$app->request->get('url')? $this->redirect( Yii::$app->request->get('url') ):$this->redirect('/wechat/index/'.$id);
            else
                Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors));

        }

        $openid = WxBase::openId($id);

        return $this->render('update',['id'=>$id,'model'=>$model]);
    }

    /*
     * ajax 请求城市联动
     */
    public function actionAjax($id)
    {
        $data  = (new \yii\db\Query())
            ->select('id,name')
            ->from('tbl_city')
            ->where('pid=:pid',[':pid'=>$id])
            ->all();

        if( !$data ) Yii::$app->end( json_encode(['status'=>0,'error'=>'没有数据'] ) );

        $str = '<option value="0">请选择城市/地区</option>';

        foreach($data as $row)
            $str .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        echo json_encode( ['status'=>1,'data'=>$str],JSON_UNESCAPED_UNICODE);
    }

    /*
     * 选择地址
     */
    public function actionSelect($id)
    {
        if( $address_id = Yii::$app->request->post('address_id')){
            $model = TblShopAddress::findOne($address_id);
            $model->add_time = time();
            if(!$model->save())
                return json_encode(['status'=>0,'msg'=>'系统出错!']);
            else
                return json_encode(['status'=>1]);

        }
        return json_encode(['status'=>0,'msg'=>'参数错误!']);
    }
}
