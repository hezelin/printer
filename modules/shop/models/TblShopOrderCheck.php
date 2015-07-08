<?php

namespace app\modules\shop\models;

use app\models\Cache;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\shop\models\TblShopOrder;

/**
 * TblShopOrderSearch represents the model behind the search form about `app\modules\shop\models\TblShopOrder`.
 */
class TblShopOrderCheck extends TblShopOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'wx_id', 'address_id', 'pay_score', 'order_status', 'pay_status', 'express', 'add_time'], 'integer'],
            [['openid', 'order_data', 'remark', 'check_word', 'express_num', 'enable'], 'safe'],
            [['freight', 'total_price'], 'number'],
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
        $query = TblShopOrder::find()->where(['tbl_shop_order.wx_id'=>Cache::getWid(),'tbl_shop_order.enable'=>'Y','tbl_shop_order.order_status'=>1])->joinWith(
            ['user','address']
        );

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
            'order_id' => $this->order_id,
            'wx_id' => $this->wx_id,
            'address_id' => $this->address_id,
            'freight' => $this->freight,
            'total_price' => $this->total_price,
            'pay_score' => $this->pay_score,
            'order_status' => $this->order_status,
            'pay_status' => $this->pay_status,
            'express' => $this->express,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'order_data', $this->order_data])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'check_word', $this->check_word])
            ->andFilterWhere(['like', 'express_num', $this->express_num])
            ->andFilterWhere(['like', 'enable', $this->enable]);

        return $dataProvider;
    }
}
