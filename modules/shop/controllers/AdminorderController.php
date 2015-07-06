<?php
/*
 * 维修申请配件
 */
namespace app\modules\shop\controllers;

use Yii;
use yii\web\Controller;

class AdminorderController extends Controller
{
    public $layout = '/console';

    /*
     * 待审核订单
     */
    public function actionCheck()
    {
        if( Yii::$app->user->isGuest ) $this->redirect(array('auth/login','url'=>'/storeOrder/check'));
        $id = WebBase::storeId();

        $model=new TblOrder('search');
        $model->unsetAttributes();
        if(isset($_GET['TblOrder'])){
            $model->attributes=$_GET['TblOrder'];
        }
        $model->dbCriteria->addCondition("store_id=$id and order_status=1 and LENGTH(open_id)<34");
        $this->render('check',array('model'=>$model));
    }

    /*
     * 批量审核通过
     */
    public function actionPut()
    {
        if(isset($_POST['mycheck']) && $_POST['mycheck']){
            Yii::$app->db->createCommand()
                ->update('tbl_order',array(
                    'order_status'=>4,
                    array('in','order_id',$_POST['mycheck'])
                ));
            echo json_encode(array('status'=>1));
            Yii::$app->end();
        }
    }

    /*
     * 审核不通过 + 留言
     */
    public function actionEditable()
    {
        if( Yii::$app->user->isGuest ) {
            header('HTTP/1.1 403');
            Yii::$app->end('请重新登录');
        }
        $id = WebBase::storeId();

        if( $r = Yii::$app->getRequest() ){
            $model = TblOrder::model()->findByPk($r->getParam('pk'));
            if($model->store_id != $id){
                header('HTTP/1.1 403');
                Yii::$app->end('没有权限');
            }
            $name = $r->getParam('name');
            // 发货状态
            if( $r->getParam('name') == 'express_num' )
                $model->order_status = 5;

            $model->$name = $r->getParam('value');
            if( !$model->save() )
                print_r( $model->errors );
        }
    }

    public function actionList()
    {
        if( Yii::$app->user->isGuest ) $this->redirect(array('auth/login','url'=>'/storeOrder/list'));
        $id = WebBase::storeId();

        $model=new TblOrder('search');
        $model->unsetAttributes();
        if(isset($_GET['TblOrder'])){
            $model->attributes=$_GET['TblOrder'];
        }
        $model->dbCriteria->addCondition("store_id=$id and LENGTH(open_id)<34");
        $this->render('list',array('model'=>$model));
    }

    /*
     * 待发货
     */
    public function actionSend()
    {
        if( Yii::$app->user->isGuest ) $this->redirect(array('auth/login','url'=>'/storeOrder/send'));
        $id = WebBase::storeId();

        $model=new TblOrder('search');
        $model->unsetAttributes();
        if(isset($_GET['TblOrder'])){
            $model->attributes=$_GET['TblOrder'];
        }
        $model->dbCriteria->addCondition("store_id=$id and order_status=4 and LENGTH(open_id)<34");
        $this->render('send',array('model'=>$model));
    }
}
