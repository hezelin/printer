<?php

namespace app\models\views;

use app\models\TblUserMaintain;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\views\ViewFaultData;
use app\models\Cache;


class ViewFaultDataSearch extends ViewFaultData
{
    public function rules()
    {
        return [
            [['id', 'weixin_id', 'machine_id', 'type', 'status', 'add_time', 'maintain_count', 'user_id'], 'integer'],
            [['desc', 'content', 'openid', 'remark', 'series_id', 'cover', 'brand_name', 'model_name', 'user_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params,$status=null)
    {
        $query = ViewFaultData::find()->where(['weixin_id'=>Cache::getWid()]);

        $process = Yii::$app->request->get('process');
        $machineId = Yii::$app->request->get('machine_id');

        if($process == 2)               // 等待评价
            $query->andWhere(['status'=>8]);
        elseif($process == 3)           // 完成中
            $query->andWhere(['status'=>9]);
        elseif($machineId)
            $query->andWhere(['machine_id'=>$machineId]);              // 查看同一台机器的微信
        else
            $query->andWhere(['<','status',8]);                        // 维修中


        if($status)
            $query->andWhere(['status'=>$status]);

        if(Yii::$app->request->get('fromFault'))
            $query->andWhere(['openid'=>Yii::$app->request->get('fromFault')]);

        if($clientNo = Yii::$app->request->get('client_no'))
            $query->andWhere(['series_id'=>$clientNo]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort'=>['defaultOrder'=>['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'weixin_id' => $this->weixin_id,
            'machine_id' => $this->machine_id,
            'type' => $this->type,
            'status' => $this->status,
            'add_time' => $this->add_time,
            'maintain_count' => $this->maintain_count,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'series_id', $this->series_id])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'model_name', $this->model_name])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }

    /*
     * 返回维修员
     */
    public function fixProvider()
    {
        return new ActiveDataProvider([
            'query' => TblUserMaintain::find()->where(['wx_id'=>Cache::getWid()]),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }
}
