<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblMachineRentProject;

/**
 * TblMachineRentProjectSearch represents the model behind the search form about `app\models\TblMachineRentProject`.
 */
class TblMachineRentProjectSearch extends TblMachineRentProject
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'wx_id', 'machine_model_id', 'is_show', 'add_time'], 'integer'],
            [['lowest_expense', 'black_white', 'colours'], 'number'],
            [['describe'], 'safe'],
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
        $query = TblMachineRentProject::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'machine_model_id' => $this->machine_model_id,
            'lowest_expense' => $this->lowest_expense,
            'black_white' => $this->black_white,
            'colours' => $this->colours,
            'is_show' => $this->is_show,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'describe', $this->describe]);

        return $dataProvider;
    }
}
