<?php
/**
 * Created by PhpStorm.
 * User: harry
 * Date: 2015/3/15
 * Time: 16:48
 */

namespace app\models;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

class DataCity {
    public static $province = array(
        0=>'选择省市',11=>'北京',12=>'天津',13=>'河北',14=>'山西',15=>'内蒙古',21=>'辽宁',22=>'吉林',23=>'黑龙江',31=>'上海',
        32=>'江苏',33=>'浙江',34=>'安徽',35=>'福建',36=>'江西',37=>'山东',41=>'河南',42=>'湖北',43=>'湖南',44=>'广东',45=>'广西',
        46=>'海南',50=>'重庆',51=>'四川',52=>'贵州',53=>'云南',54=>'西藏',61=>'陕西',62=>'甘肃',63=>'青海',64=>'宁夏',65=>'新疆',
        71=>'台湾',81=>'香港',91=>'澳门'
    );

    public static $city = array(
        0=>'选择城市',
    );

    public static $region = array(
        0=>'选择地区',
    );

    /*
     * 城市树的id / 树的名字
     */
    public static function getTree($treeId,$treeName)
    {
        $name = $treeName === 'city'? '选择城市':'选择地区';
        $targetName = $treeName === 'city'? 'region':'';
        $data = (new \yii\db\Query())
            ->select('id, name')
            ->from('tbl_city')
            ->where('pid=:pid',array(':pid'=>$treeId))
            ->all();

        $data = [0 => $name] + ArrayHelper::map($data,'id','name');

        return Html::dropDownList('city','',$data,[
            'class'=>'form-control th-'.$treeName,
            'tree-name'=>$targetName,
            'tree-target'=>'#wrap-'.$targetName,
        ]);
    }
} 