<?php

namespace app\controllers;
use app\models\LogUserLoginSearch;
use app\models\MemberForm;
use app\models\TblUser;
use app\models\TblUserSearch;
use Yii;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\filters\AccessControl;

class AdminRbacController extends \yii\web\Controller
{
    public $layout = '/weixin';

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['super'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionMember()
    {
        /*if(Yii::$app->user->can('super'))
            echo '有权限';
        else
            echo '没有权限';
        exit('sfwefff');*/

        $searchModel = new TblUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('member', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /*
     * 添加成员
     */
    public function actionMemberCreate()
    {
        $model = new MemberForm();

        if ( $model->load(Yii::$app->request->post()) ) {

            if($model->validate())
                if($model->signup()){
                    return $this->redirect('member');
                }
            else
                Yii::$app->session->setFlash('error','未知错误');
        }
        $weixinIds = (new \yii\db\Query())
            ->select('id,name')
            ->from('tbl_weixin')
            ->where(['uid'=>Yii::$app->user->id])
            ->all();
        $weixinIds = ArrayHelper::map($weixinIds,'id','name');
        return $this->render('member-create',[
            'model' => $model,
            'weixinIds' => $weixinIds,
        ]);
    }

    /*
     * 修改成员
     */
    public function actionUpdate($id)
    {
        $model = TblUser::findOne($id);
        if(!$model)
            throw new HttpException(401,'成员不存在');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['member']);
        }

        $weixinIds = (new \yii\db\Query())
            ->select('id,name')
            ->from('tbl_weixin')
            ->where(['uid'=>Yii::$app->user->id])
            ->all();
        $weixinIds = ArrayHelper::map($weixinIds,'id','name');
        return $this->render('member-update',[
            'model' => $model,
            'weixinIds' => $weixinIds,
        ]);
    }
    /*
     * 删除成员
     */
    public function actionDelete($id)
    {
        $model = TblUser::findOne($id);
        if(!$model)
            throw new HttpException(401,'成员不存在');
        if($model->group_id != Yii::$app->user->id)
            throw new HttpException(403,'没有权限删除');
        $model->delete();
        return $this->redirect(['member']);
    }

    /*
     * 成员登录日志
     */
    public function actionLog()
    {
        $searchModel = new LogUserLoginSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('log', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
