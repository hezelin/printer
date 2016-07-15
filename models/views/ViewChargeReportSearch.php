<?php

namespace app\models\views;

use app\models\Cache;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\views\ViewChargeReport;


class ViewChargeReportSearch extends ViewChargeReport
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'wx_id', 'machine_id', 'colour', 'black_white', 'status', 'add_time', 'first_rent_time', 'rent_period'], 'integer'],
            [['total_money', 'exceed_money'], 'number'],
            [['sign_img', 'name', 'user_name', 'address', 'model_name', 'brand_name', 'series_id'], 'safe'],
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
        $query = ViewChargeReport::find()->where(['wx_id'=>Cache::getWid()]);

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
            'wx_id' => $this->wx_id,
            'machine_id' => $this->machine_id,
            'colour' => $this->colour,
            'black_white' => $this->black_white,
            'total_money' => $this->total_money,
            'exceed_money' => $this->exceed_money,
            'status' => $this->status,
            'add_time' => $this->add_time,
            'first_rent_time' => $this->first_rent_time,
            'rent_period' => $this->rent_period,
        ]);

        $query->andFilterWhere(['like', 'sign_img', $this->sign_img])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'model_name', $this->model_name])
            ->andFilterWhere(['like', 'brand_name', $this->brand_name])
            ->andFilterWhere(['like', 'series_id', $this->series_id]);

        return $dataProvider;
    }
}
