<?php
/*
 * 维修申请配件
 */
namespace app\modules\shop\controllers;

use app\models\ToolBase;
use app\modules\shop\models\TblParts;
use app\modules\shop\models\TblPartsLog;
use app\modules\shop\models\TblPartsLogSearch;
use app\modules\shop\models\TblPartsRecycle;
use app\modules\shop\models\TblPartsSearch;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class AdminpartsController extends Controller
{
    public $layout = '/console';

    /*
     * 维修配件列表
     */
    public function actionList()
    {
        $searchModel = new TblPartsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 已回收配件
     */
    public function actionRecycle()
    {
        $searchModel = new TblPartsRecycle();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('recycle', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /*
     * 更改机器的状态
     */
    public function actionStatus($id,$status)
    {
        $model = TblParts::findOne($id);
        if($model->status == 2 && !Yii::$app->request->get('fault_id')){
            return $this->redirect(['/service/select','url'=>Yii::$app->request->url]);
        }
        $model->status = $status;
        if( Yii::$app->request->get('fault_id') )
            $model->fault_id = Yii::$app->request->get('fault_id');

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->save();
            $model->process();
            if($model->status == 10 ){
                $model->updateBind();
            }
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            if(Yii::$app->request->isAjax)
                return json_encode(['status'=>0,'msg'=>'入库失败!']);
            return $e;
        }
        if(Yii::$app->request->isAjax)
            return json_encode(['status'=>1]);
        return $this->redirect( Yii::$app->request->referrer );
    }

    /*
     * 配件留言记录
     */
    public function actionLog($id)
    {
        $searchModel = new TblPartsLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$id);

        return $this->render('log', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /*
     * 配件备注提交
     */
    public function actionRemark($id)
    {
        if($text = Yii::$app->request->post('text')){
            $model = new TblPartsLog();
            $model->parts_id = $id;
            $model->content =$text;
            $model->status = 13;
            $model->add_time = time();
            if( $model->save() )
                return json_encode(['status'=>1]);
            else
                return json_encode(['status'=>0,'msg'=>'入库失败！'.ToolBase::arrayToString($model->errors)]);
        }else
            return json_encode(['status'=>0,'msg'=>'提交参数错误！']);
    }
    /*
     * 配件删除
     */
    public function actionDelete($id)
    {
        $model = TblParts::findOne($id);
        $model->enable = 'N';
        $model->save();

        return $this->redirect(['list']);
    }


}
