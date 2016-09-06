<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblRentApply;

/**
 * TblRentApplySearch represents the model behind the search form about `app\models\TblRentApply`.
 */
class TblRentApplyCollect extends TblRentApply
{
    public $nickname;

    public function rules()
    {
        return [
            [['id', 'wx_id', 'project_id', 'machine_id', 'due_time', 'status', 'add_time','first_rent_time', 'rent_period'], 'integer'],
            [['openid', 'phone', 'name', 'address', 'apply_word'], 'safe'],
            [['monthly_rent', 'black_white', 'colours', 'latitude', 'longitude', 'accuracy'], 'number'],
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
        $query = (new \yii\db\Query())
            ->select('t.id,t.name,t.phone,t.add_time,t.first_rent_time,u.nickname,u.headimgurl,u.sex,
                t.monthly_rent,t.black_white,t.colours,m.brand_name,m.model_name,m.images')
            ->from('tbl_rent_apply as t')
            ->leftJoin('tbl_user_wechat as u','u.openid=t.openid')
            ->leftJoin('tbl_machine as m','t.machine_id=m.id')
            ->where(['t.wx_id'=>Cache::getWid()])
            ->andWhere(['<','t.first_rent_time',time()+86400*3])
            ->andWhere(['<','t.status',11]);

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
            'id' => $this->id,
            'wx_id' => $this->wx_id,
            'project_id' => $this->project_id,
            'machine_id' => $this->machine_id,
            'monthly_rent' => $this->monthly_rent,
            'black_white' => $this->black_white,
            'colours' => $this->colours,
            'due_time' => $this->due_time,
            'first_rent_time' => $this->first_rent_time,
            'rent_period' => $this->rent_period,
            'status' => $this->status,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'accuracy' => $this->accuracy,
            'add_time' => $this->add_time,
        ]);

        $query->andFilterWhere(['like', 'openid', $this->openid])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'apply_word', $this->apply_word])
            ->andFilterWhere(['like', 'tbl_user_wechat.nickname', $this->nickname]);

        return $dataProvider;
    }
}
