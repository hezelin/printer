<?php

namespace app\models\views;

use app\models\Cache;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class ViewMachineModelSearch extends ViewMachineModel
{
    public function rules()
    {
        return [
            [['id', 'wx_id', 'model_id', 'come_from', 'status', 'maintain_count', 'rent_count', 'add_time'], 'integer'],
            [['series_id', 'buy_date', 'cover', 'model', 'brand_name'], 'safe'],
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
        $query = ViewMachineModel::find()
            ->where(['wx_id'=>Cache::getWid()])
            ->andWhere(['<','status',11])
            ->orderBy('add_time desc');

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
            'come_from' => $this->come_from,
            'status' => $this->status,
            'maintain_count' => $this->maintain_count,
            'rent_count' => $this->rent_count,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'series_id', $this->series_id])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'brand_name', $this->brand_name]);

        return $dataProvider;
    }
}
