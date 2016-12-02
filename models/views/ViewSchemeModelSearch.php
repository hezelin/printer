<?php

namespace app\models\views;

use app\models\Cache;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\views\ViewSchemeModel;

class ViewSchemeModelSearch extends ViewSchemeModel
{
    public function rules()
    {
        return [
            [['id', 'wx_id', 'machine_model_id', 'contain_paper', 'is_show', 'add_time'], 'integer'],
            [['lowest_expense', 'black_white', 'colours'], 'number'],
            [['cover', 'images', 'describe', 'brand_name', 'model'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ViewSchemeModel::find()->where(['wx_id'=>Cache::getWid()]);

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
            'machine_model_id' => $this->machine_model_id,
            'lowest_expense' => $this->lowest_expense,
            'contain_paper' => $this->contain_paper,
            'black_white' => $this->black_white,
            'colours' => $this->colours,
            'is_show' => $this->is_show,
            'add_time' => $this->add_time,
        ])->orderBy('add_time desc');//20161130 biao 修改显示顺序

        $query->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'images', $this->images])
            ->andFilterWhere(['like', 'describe', $this->describe])
            ->andFilterWhere(['like', 'brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'model', $this->model]);

        return $dataProvider;
    }
}
