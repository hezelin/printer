<?php
/*
 * 调试公共类
 * @author harry
 * @date 2016-9-7
 */
namespace app\models\common;

use Yii;

class Debug
{
    public static $num = 1;

    public static function text($data)
    {
        $message = '';
        if(is_object($data))
        {
            foreach( get_object_vars($data) as  $k=>$v)
            {
                if(is_array($v))
                    $message .= self::text($v);
                else
                    $message .= "$k=>$v\n";
            }
        }
        elseif(is_array($data))
        {
            foreach($data as $k=>$v)
            {
                if(is_array($v))
                    $message .= self::text($v);
                else
                    $message .= "$k=>$v\n";
            }
        }else
            $message = $data;
        return $message;
    }


    public static function log($data,$name = 'weixin.log')
    {
        $message = self::text($data);
        $dir = \Yii::getAlias('@runtime');
        $name = '/'.$name;
        file_put_contents($dir.$name,$message,FILE_APPEND);
    }

    public static function output($data,$line=false,$end=false)
    {
        $message = self::text($data);
        if($line)
            echo '----'.self::$num++.'---------------';
        echo '<pre>';
        print_r($message);
        echo '</pre>';
        $end && exit;
    }
} 