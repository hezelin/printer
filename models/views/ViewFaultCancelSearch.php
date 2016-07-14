<?php

namespace app\models\views;

use app\models\Cache;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\views\ViewFaultCancel;


class ViewFaultCancelSearch extends ViewFaultCancel
{
    public function rules()
    {
        return [
            [['id', 'wx_id', 'service_id', 'status', 'type', 'add_time', 'fault_type', 'apply_time'], 'integer'],
            [['opera', 'opera_name', 'reason', 'content', 'desc', 'remark'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ViewFaultCancel::find()->where(['wx_id'=>Cache::getWid()]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'wx_id' => $this->wx_id,
            'service_id' => $this->service_id,
            'status' => $this->status,
            'type' => $this->type,
            'add_time' => $this->add_time,
            'fault_type' => $this->fault_type,
            'apply_time' => $this->apply_time,
        ]);

        $query->andFilterWhere(['like', 'opera', $this->opera])
            ->andFilterWhere(['like', 'opera_name', $this->opera_name])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
