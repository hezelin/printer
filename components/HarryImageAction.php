<?php
/**
 * 图片处理器
 * @author harry he
 * @time 2015-12-3 11:00
 */
namespace app\components;

use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;

class HarryImageAction extends Action
{
    public $dirName = '@app/web/';
    public $pathRoute = 'uploads/';
    public $pathName = 'site';
    public $bigSize = [600,9000];
    public $smallSize = [150,1000];


    public function init()
    {
        $this->controller->enableCsrfValidation = false;
        $this->dirName = Yii::getAlias($this->dirName);
        if( $path = Yii::$app->request->get('pathName'))
            $this->pathName = $path;
        if (!is_dir($this->dirName)) {
            throw new InvalidConfigException("路径不存在: {$this->dirName}");
        }
    }

    /**
     * Runs the action.
     */
    public function run()
    {
        //收集图片 每一个月份为一个文件夹
        $subPath='/'.$this->pathRoute.$this->pathName.'/'.date('ym').'/'.date('d').'/';

        // 新建路径
        $imgdir['o'] = $this->newDir($subPath.'o/', $this->dirName );
        $imgdir['m'] = $this->newDir($subPath.'m/', $this->dirName );
        $imgdir['s'] = $this->newDir($subPath.'s/', $this->dirName );

        $path=pathinfo($_FILES['uploadfile']['name']);
        $type=strtolower($path['extension']);														//文件 扩展名
        $picType=array('jpg','png','jpeg','gif');

        // 判断图片格式
        if( !in_array( $type,$picType) )
            return json_encode( ['status'=>0,'msg'=>'输入图片格式不对']);

        $filename=uniqid( $this->getSalt(7) ).'.'.$type;

        $MAX_SIZE = 5242880;
        if($_FILES['uploadfile']['size'] > $MAX_SIZE)
            return json_encode( ['status'=>0,'msg'=>'上传的图片大小超过了规定的5M']);
        if($_FILES['uploadfile']['size'] == 0)
            return json_encode( ['status'=>0,'msg'=>'上传的图片大小超过了规定的5M']);

        if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $imgdir['o'].$filename)){
            $this->mt($imgdir['o'].$filename, $imgdir['m'].$filename,$this->bigSize[0],$this->bigSize[1]);
            $this->mt($imgdir['o'].$filename, $imgdir['s'].$filename,$this->smallSize[0],$this->smallSize[1]);
            return  json_encode( ['status'=>1,'url'=>$subPath.'s/'.$filename] );
        }
    }

    /*
     * 循环嵌套建立目录
     */
    private function newDir($dir,$preDir=''){
        $parts = explode('/', $dir);
        $dir = $preDir;
        foreach($parts as $part)
            if(!is_dir($dir .= "/$part")) mkdir($dir,0755);
        return $dir;
    }

    /*
     * 获取随机数
     */
    public static function getSalt($len=8)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        for($i=0,$salt='';$i<$len;$i++)
            $salt.=$str[mt_rand(0,61)];
        return $salt;
    }

    /*
     * 图片压缩
     * @param string $srcFile 图片源包含绝对路径,
     * @param string $dstFile 压缩后图片路径包含图片名
     * @param int : $max_width 宽度，$max_height 高度，$imgQuality 清晰度
     */
    private function mt($srcFile,$dstFile,$max_width=150,$max_height=150,$imgQuality=90){
        ini_set("gd.jpeg_ignore_warning", 1);
        $data=@getimagesize($srcFile);
        if(  ($data[1]*$max_width-$data[0]*$max_height)>=0 && $data[1]>$max_height ){
            $height=$max_height;
            $width=intval($height*$data[0]/$data[1]);
        }
        else if(  ($data[1]*$max_width-$data[0]*$max_height)<0 && $data[0]>$max_width ){
            $width=$max_width;
            $height=intval($width*$data[1]/$data[0]);
        }
        else if( $data[0]<=$max_width && $data[1]<=$max_height){
            $width=$data[0];
            $height=$data[1];
        }

        ini_set("memory_limit", "400M");            // 解决 imagecreatefromgif 对内存的贪婪
        switch($data[2]){
            case 1:
                $im=@imagecreatefromgif($srcFile);
                break;
            case 2:
                $im=@imagecreatefromjpeg($srcFile);
                break;
            case 3:
                $im=@imagecreatefrompng($srcFile);
                break;
        }
        $srcW=@imagesx($im);
        $srcH=@imagesy($im);
        $ni=@imagecreatetruecolor($width,$height);
        @imagecopyresampled($ni,$im,0,0,0,0,$width,$height,$srcW,$srcH);
        switch($data[2]){
            case 'gif':@imagepng($ni,$dstFile, $imgQuality); break;
            case 'jpeg':@imagejpeg($ni,$dstFile, $imgQuality); break;
            case 'png':@imagepng($ni,$dstFile, $imgQuality); break;
            default:@imagejpeg($ni,$dstFile, $imgQuality); break;
        }
        return array('width'=>$width,'height'=>$height);
    }
}
