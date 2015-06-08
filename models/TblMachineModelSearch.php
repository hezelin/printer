<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblMachineModel;

/**
 * TblMachineModelSearch represents the model behind the search form about `app\models\TblMachineModel`.
 */
class TblMachineModelSearch extends TblMachineModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'wx_id', 'brand_id', 'add_time', 'machine_count', 'is_color'], 'integer'],
            [['type', 'cover', 'cover_images', 'buy_date', 'function', 'else_attr', 'describe', 'enable'], 'safe'],
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
        $query = TblMachineModel::find()->where(['wx_id'=>Cache::getWid(),'enable'=>'Y']);

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
            'brand_id' => $this->brand_id,
            'buy_date' => $this->buy_date,
            'add_time' => $this->add_time,
            'machine_count' => $this->machine_count,
            'is_color' => $this->is_color,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'cover_images', $this->cover_images])
            ->andFilterWhere(['like', 'function', $this->function])
            ->andFilterWhere(['like', 'else_attr', $this->else_attr])
            ->andFilterWhere(['like', 'describe', $this->describe])
            ->andFilterWhere(['like', 'enable', $this->enable]);

        return $dataProvider;
    }
}
