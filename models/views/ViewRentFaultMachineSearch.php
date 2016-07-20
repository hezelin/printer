<?php

namespace app\models\views;

use app\models\Cache;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class ViewRentFaultMachineSearch extends ViewRentFaultMachine
{

    public function rules()
    {
        return [
            [['machine_id', 'wx_id', 'due_time', 'first_rent_time', 'add_time', 'come_from', 'status', 'fault_id'], 'integer'],
            [['openid', 'phone', 'name', 'address', 'series_id', 'cover', 'brand_name', 'model_name'], 'safe'],
            [['monthly_rent', 'black_white', 'colours', 'latitude', 'longitude'], 'number'],
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
        $query = ViewRentFaultMachine::find()->where(['wx_id'=>Cache::getWid()]);

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
            'machine_id' => $this->machine_id,
            'wx_id' => $this->wx_id,
            'monthly_rent' => $this->monthly_rent,
            'black_white' => $this->black_white,
            'colours' => $this->colours,
            'due_time' => $this->due_time,
            'first_rent_time' => $this->first_rent_time,
            'add_time' => $this->add_time,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'come_from' => $this->come_from,
            'status' => $this->status,
            'fault_id' => $this->fault_id,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'series_id', $this->series_id])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'model_name', $this->model_name]);

        return $dataProvider;
    }
}
