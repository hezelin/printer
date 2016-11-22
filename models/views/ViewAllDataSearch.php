<?php

namespace app\models\views;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\views\ViewAllData;
use app\models\Cache;

/**
 * ViewAllDataSearch represents the model behind the search form about `app\models\views\ViewAllData`.
 */
class ViewAllDataSearch extends ViewAllData
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rent_id', 'machine_id', 'wx_id', 'contain_paper', 'due_time', 'first_rent_time', 'add_time', 'apply_status', 'come_from', 'fault_id', 'status'], 'integer'],
            [['openid', 'phone', 'name', 'address', 'series_id', 'cover', 'brand_name', 'model_name', 'nickname', 'headimgurl'], 'safe'],
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

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ViewAllData::find()
        ->where(['wx_id'=>Cache::getWid()])
        ->andWhere(['>','apply_status',1]);

        if($clientNo = Yii::$app->request->get('client_no'))
            $query->andWhere(['series_id'=>$clientNo]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['add_time' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'rent_id' => $this->rent_id,
            'machine_id' => $this->machine_id,
            'wx_id' => $this->wx_id,
            'monthly_rent' => $this->monthly_rent,
            'contain_paper' => $this->contain_paper,
            'black_white' => $this->black_white,
            'colours' => $this->colours,
            'due_time' => $this->due_time,
            'first_rent_time' => $this->first_rent_time,
            'add_time' => $this->add_time,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'apply_status' => $this->apply_status,
            'come_from' => $this->come_from,
            'fault_id' => $this->fault_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'series_id', $this->series_id])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'model_name', $this->model_name])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'headimgurl', $this->headimgurl]);

        return $dataProvider;
    }
}
