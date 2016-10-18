<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblMachine;

class TblMachineSearch extends TblMachine
{
    public function rules()
    {
        return [
            [['id', 'wx_id', 'model_id', 'status', 'maintain_count', 'rent_count', 'add_time', 'come_from'], 'integer'],
            [['model_name', 'brand', 'brand_name', 'series_id', 'buy_date', 'cover', 'images', 'remark'], 'safe'],
            [['buy_price'], 'number'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = TblMachine::find()
            ->where(['wx_id'=>Cache::getWid()])
            ->andWhere(['<','status',11])
            ->orderBy('id desc');

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
            'wx_id' => $this->wx_id,
            'model_id' => $this->model_id,
            'buy_price' => $this->buy_price,
            'buy_date' => $this->buy_date,
            'status' => $this->status,
            'maintain_count' => $this->maintain_count,
            'rent_count' => $this->rent_count,
            'add_time' => $this->add_time,
            'come_from' => $this->come_from,
        ]);

        $query->andFilterWhere(['like', 'model_name', $this->model_name])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'series_id', $this->series_id])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'images', $this->images])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
