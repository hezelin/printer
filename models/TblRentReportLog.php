<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblRentReport;

/**
 * TblRentReportSearch represents the model behind the search form about `app\models\TblRentReport`.
 */
class TblRentReportLog extends TblRentReport
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'wx_id', 'machine_id', 'colour', 'black_white', 'status', 'add_time'], 'integer'],
            [['total_money', 'exceed_money'], 'number'],
            [['sign_img', 'name', 'enable'], 'safe'],
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
        $query = TblRentReport::find()
            ->where('wx_id=:wid and machine_id=:mid',[':wid'=>Cache::getWid(),':mid'=>Yii::$app->request->get('machine_id')]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=>['defaultOrder'=>['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

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
        ]);

        $query->andFilterWhere(['like', 'sign_img', $this->sign_img])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'enable', $this->enable]);

        return $dataProvider;
    }
}
