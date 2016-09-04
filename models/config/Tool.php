<?php
/**
 * 全局功能 工具
 */
namespace app\models\config;
use yii\helpers\Html;


class Tool
{
    public static function getImage($url,$size=100,$magnify=false,$multi=false)
    {
        $tmp = [];
        if($multi){
            $urls = json_decode($url,true);
            if(!is_array($urls))
                return null;
        }else
            $urls[] = $url;
        foreach($urls as $url){
            if($magnify)
                $tmp[] = Html::a(
                    Html::img(str_replace('/m/','/s/',$url), ['style'=>'width:'.$size.'px']),
                    str_replace('/s/','/m/',$url),
                    ['class'=>'fancybox','rel'=>'group']
                );
            else
                $tmp[] = Html::img($url,['style'=>'width:'.$size.'px']);
        }
        return join('&nbsp;&nbsp;',$tmp);
    }

    /*
     * 租赁价格转换 分钱转换
     */
    public static function schemePrice($price)
    {
        $data = $price * 100;
        if(!$data)
            return '无';
        if($data<0)
            return '无';
        else if($data>=10)
            return ($data/10).'毛/张';
        else
            return $data.'分/张';
    }

    /*
     * 张数转换
     */
    public static function paperNum($num)
    {
        if($num>1000)
            return $num/1000 .'千张';
        else
            return $num .'张';
    }

    public static function log($data)
    {
        $message = '';
        if(is_object($data))
        {
            foreach( get_object_vars($data) as  $k=>$v)
            {
                if(is_array($v))
                    $message .= self::log($v);
                else
                    $message .= "$k=>$v\n";
            }
        }
        elseif(is_array($data))
        {
            foreach($data as $k=>$v)
            {
                if(is_array($v))
                    $message .= self::log($v);
                else
                    $message .= "$k=>$v\n";
            }
        }else
            $message = $data;

        $dir = \Yii::getAlias('@runtime');
        $name = '/weixin.log';
        file_put_contents($dir.$name,$message,FILE_APPEND);
    }

    public static function location($openid,$wx_id,$longitude,$latitude)
    {
        if(strlen($openid) == 28)
        {
            $t = time();
            $sql = "UPDATE `tbl_user_maintain` SET `longitude`=$longitude ,`latitude`=$latitude ,`point_time`=$t WHERE openid='$openid' and wx_id=$wx_id";
            \Yii::$app->db->createCommand($sql)->execute();
        }
    }
}