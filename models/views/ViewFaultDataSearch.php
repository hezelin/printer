<?php

namespace app\models\views;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\views\ViewFaultData;


class ViewFaultDataSearch extends ViewFaultData
{

    public function rules()
    {
        return [
            [['id', 'weixin_id', 'type', 'status', 'add_time', 'maintain_count', 'user_id'], 'integer'],
            [['desc', 'content', 'cover', 'name', 'user_name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ViewFaultData::find();

        // add conditions that should always apply here

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
            'weixin_id' => $this->weixin_id,
            'type' => $this->type,
            'status' => $this->status,
            'add_time' => $this->add_time,
            'maintain_count' => $this->maintain_count,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'user_name', $this->user_name]);

        return $dataProvider;
    }
}
