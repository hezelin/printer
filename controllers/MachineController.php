<?php

namespace app\controllers;
use app\models\Cache;
use app\models\common\Debug;
use app\models\MachineRent;
use app\models\TblMachineSearch;
use app\models\TblRentApply;
use Yii;
use app\models\TblMachine;
use app\models\ToolBase;
use yii\base\Exception;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;


class MachineController extends \yii\web\Controller
{
    public $layout = 'console';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'force-delete' => ['post'],
                ],
            ],
        ];
    }

    /*
     * 机器图片不能为空，机器的品牌不能为空
     */
    public function actionEditable()
    {
        $model = TblMachine::findOne($_POST['editableKey']);
        if(!$model){
            return ['output'=>'','message'=>'数据库错误'];
        }

        if (isset($_POST['hasEditable'])) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $value = $_POST['TblMachine'][$_POST['editableIndex']][$_POST['editableAttribute']];
            $model->$_POST['editableAttribute'] = $value;
            if(!$model->cover){                             // 封面图片不存在
                $model->images = json_encode(['/img/haoyizu.png']);
                $model->cover = '/img/haoyizu.png';
            }
            $model->model_id || $model->model_id = 1664;        //-
            $model->brand || $model->brand = 'wsz';             //-

            if($model->save())
                return ['output'=>$value, 'message'=>''];
            return ['output'=>'','message'=>'数据库错误'];
        }
    }

    public function actionAdd()
    {
        $model = new TblMachine(['scenario' => 'create']);

        if ($model->load(Yii::$app->request->post())) {

            if( !$model->validate() ){                              // 验证数据是否完整
                return $this->render('add', ['model' => $model ]);
            }
            if( $model->amount == 1 ){
                if( $model->save(false) ){
                    return $this->redirect(['view', 'id' => $model->id]);
                }
                else
                    Yii::$app->session->setFlash('error',ToolBase::arrayToString($model->errors) );
            }else{
                $row =$model->multiSave();                          // 批量插入，并且自动更新统计
                if($row)
                {
//                    $this->refresh();
//                    Yii::$app->session->setFlash('success','成功批量添加 '.$row.' 个机器');
                    return $this->render('//tips/success',[
                        'tips'=>'成功批量添加 '.$row.' 个机器',
                        'btnText'=>'继续添加',
                        'btnUrl'=>Url::toRoute(['add'])
                    ]);
                }
            }

        }

        if(Yii::$app->request->get('model_id'))
            $model->model_id = Yii::$app->request->get('model_id');

        $model->come_from = 1;
        return $this->render('add', ['model' => $model]);

    }

    public function actionDelete($id)
    {
        /*
        $model = $this->findModel($id);
        $model->status = 11;
        $model->save();

        return $this->redirect(['list']);
        */

        //[20161216 删除机器同时删除租赁关系
        $transaction = Yii::$app->db->beginTransaction();
        try{
            //1. 删除机器表（更新状态）
            $model = $this->findModel($id);
            $model->status = 11;
            if(!$model->save()) {
                Debug::log("机器状态出错\r\n");
                throw new Exception("机器状态出错");
            }

            //2. 删除租赁关系（更新状态）
            $sql = "UPDATE `tbl_rent_apply` SET `status` = 11 WHERE `wx_id`= ".Cache::getWid()." and `machine_id`= ".trim($id);

            if(!Yii::$app->db->createCommand($sql)->execute()){
                Debug::log("租赁状态出错\r\n");
                Debug::log(Yii::$app->db->createCommand($sql)->getSql()."\r\n");
                throw new Exception("租赁关系状态出错");
            }
            $transaction->commit();
        }catch (\Exception $e){
            $transaction->rollBack();
            throw new Exception("系统出错");
        }

        return $this->redirect(['list']);
        //20161216]
    }

    /*
     * 删除真实数据
     */
    public function actionForceDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect('/code/ahead');
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionList()
    {
        $searchModel = new TblMachineSearch();
        $params = Yii::$app->request->queryParams;
        if( !isset($params['TblMachineSearch']['come_from']) )
            $params['TblMachineSearch']['come_from'] = 1;

        $dataProvider = $searchModel->search($params);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 预设机器
     */
    public function actionPreList()
    {
        $searchModel = new TblMachineSearch();
        $params = Yii::$app->request->queryParams;
        if( !isset($params['TblMachineSearch']['come_from']) )
            $params['TblMachineSearch']['come_from'] = 4;

        $dataProvider = $searchModel->search($params);

        return $this->render('pre-list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionStatus()
    {
        return $this->render('status');
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else
            return $this->render('update', [ 'model' => $model ]);

    }

    /*
     * $id  为机器 machine_id
     */
    public function actionUpdateRent($id)
    {
        $model = new MachineRent($id);
        if(Yii::$app->request->post())
        {
            if( $model->save() == 'success' ){
                Yii::$app->session->setFlash('success','资料录入成功！，请更正用户坐标！');
                return $this->redirect(['/admin-rent/map','id'=>$model->rent->id]);
            }else
                Yii::$app->session->setFlash('error','资料录入失败！');
        }

        return $this->render('machine-rent',[
            'machine'=>$model->machine,
            'rent'=>$model->rent,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = TblMachine::findOne(['id' => $id, 'wx_id' => Cache::getWid()])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }

}
