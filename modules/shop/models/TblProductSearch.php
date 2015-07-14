<?php

namespace app\modules\shop\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\TblProduct;
use app\models\Cache;
/**
 * TblProductSearch represents the model behind the search form about `app\modules\shop\models\TblProduct`.
 */
class TblProductSearch extends TblProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'wx_id', 'category_id', 'amount', 'use_score', 'give_score', 'add_time', 'opera_time', 'status'], 'integer'],
            [['name', 'cover', 'cover_images', 'describe', 'add_attr', 'enable'], 'safe'],
            [['market_price', 'price'], 'number'],
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
        $query = TblProduct::find()->where(['wx_id'=>Cache::getWid(),'enable'=>'Y']);

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
            'category_id' => $this->category_id,
            'market_price' => $this->market_price,
            'price' => $this->price,
            'amount' => $this->amount,
            'use_score' => $this->use_score,
            'give_score' => $this->give_score,
            'add_time' => $this->add_time,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'cover', $this->cover])
            ->andFilterWhere(['like', 'cover_images', $this->cover_images])
            ->andFilterWhere(['like', 'describe', $this->describe])
            ->andFilterWhere(['like', 'add_attr', $this->add_attr])
            ->andFilterWhere(['like', 'enable', $this->enable]);

        return $dataProvider;
    }
}
