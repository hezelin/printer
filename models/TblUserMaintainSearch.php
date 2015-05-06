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
            [['wx_id', 'add_time'], 'integer'],
            [['openid','nickname'], 'safe'],
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
        $query = TblUserMaintain::find(['wx_id'=>Cache::getWid()])->joinWith('userinfo');

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
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid]);
        $query->andFilterWhere(['like', 'tbl_user_wechat.nickname', $this->nickname]);

        return $dataProvider;
    }
}