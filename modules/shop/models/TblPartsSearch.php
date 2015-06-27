<?php

namespace app\modules\shop\models;

use app\models\Cache;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\TblParts;

/**
 * TblPartsSearch represents the model behind the search form about `app\modules\shop\models\TblParts`.
 */
class TblPartsSearch extends TblParts
{
    public $name;
    public $market_price;
    public $price;
    public function rules()
    {
        return [
            [['id', 'wx_id', 'item_id', 'fault_id', 'status', 'apply_time', 'bing_time','market_price','price'], 'integer'],
            [['openid', 'remark', 'enable','name'], 'safe'],
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
        $query = TblParts::find()
            ->joinWith(['product','maintainer','fault'])
            ->where(['tbl_parts.wx_id'=>Cache::getWid(),'tbl_parts.enable'=>'Y'])
            ->andWhere(['<','tbl_parts.status',12]);

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
            'item_id' => $this->item_id,
            'fault_id' => $this->fault_id,
            'tbl_parts.status' => $this->status,
            'tbl_parts.apply_time' => $this->apply_time,
            'tbl_parts.bing_time' => $this->bing_time,
            'tbl_product.market_price' => $this->market_price,
            'tbl_product.price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'tbl_parts.remark', $this->remark])
            ->andFilterWhere(['like', 'enable', $this->enable])
            ->andFilterWhere(['like', 'tbl_product.name', $this->name]);

        return $dataProvider;
    }
}
