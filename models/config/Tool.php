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
}