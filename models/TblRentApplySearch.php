<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\TblRentApply;

/**
 * TblRentApplySearch represents the model behind the search form about `app\models\TblRentApply`.
 */
class TblRentApplySearch extends TblRentApply
{
    public $nickname;

    public function rules()
    {
        return [
            [['id', 'wx_id', 'project_id', 'machine_id', 'due_time', 'status', 'add_time','first_rent_time', 'rent_period'], 'integer'],
            [['openid', 'phone', 'name', 'address', 'apply_word', 'enable'], 'safe'],
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
        /*$query = TblRentApply::find()
            ->where(['tbl_rent_apply.wx_id'=>Cache::getWid(),'tbl_rent_apply.status'=>1,'tbl_rent_apply.enable'=>'Y'])
            ->joinWith('userInfo');*/
        $query = (new \yii\db\Query())
            ->select('t.id,t.name,t.phone,t.add_time,u.nickname,u.headimgurl,u.sex,
                p.lowest_expense,p.black_white,p.colours,m.type,m.cover_images,m.is_color')
            ->from('tbl_rent_apply as t')
            ->where('t.wx_id=:wid and t.status=1 and t.enable="Y"',[':wid'=>Cache::getWid()])
            ->leftJoin('tbl_user_wechat as u','u.openid=t.openid')
            ->leftJoin('tbl_machine_rent_project as p','p.id=t.project_id')
            ->leftJoin('tbl_machine_model as m','p.machine_model_id=m.id');

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
            ->andFilterWhere(['like', 'enable', $this->enable])
            ->andFilterWhere(['like', 'tbl_user_wechat.nickname', $this->nickname]);

        return $dataProvider;
    }
}
