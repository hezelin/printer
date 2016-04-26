<?php
/*
 * 维修申请配件
 */
namespace app\modules\shop\controllers;

use app\models\ToolBase;
use app\modules\shop\models\TblParts;
use app\modules\shop\models\TblPartsLog;
use app\modules\shop\models\TblPartsLogSearch;
use app\modules\shop\models\TblPartsSearch;
use Yii;
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
     * 更改配件的状态
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
    public function actionLog($wx_id,$item_id,$un)
    {
        $searchModel = new TblPartsLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
        if($text = Yii::$app->request->post('content')){
            $model = new TblPartsLog();
            if($model->remark())
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
