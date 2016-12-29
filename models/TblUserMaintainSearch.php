<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblUserMaintain;

/**
 * TblUserMaintainSearch represents the model behind the search form about `app\models\TblUserMaintain`.
 */
class TblUserMaintainSearch extends TblUserMaintain
{
    public $nickname;

    public function rules()
    {
        return [
            [['wx_id', 'wait_repair_count', 'add_time', 'point_time'], 'integer'],
            [['openid', 'name', 'phone','nickname', 'identity_card', 'address'], 'safe'],
            [['latitude', 'longitude'], 'number'],
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
        $query = TblUserMaintain::find()->where(['tbl_user_maintain.wx_id'=>Cache::getWid(), 'status' => 10])->joinWith('userinfo');//20161228 添加状态字段

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'wx_id' => $this->wx_id,
            'wait_repair_count' => $this->wait_repair_count,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'point_time' => $this->point_time,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'identity_card', $this->identity_card])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'tbl_user_wechat.nickname', $this->nickname]);

        return $dataProvider;
    }
}
