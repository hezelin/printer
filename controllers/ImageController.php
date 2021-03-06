<?php

/*
 * 图片处理控制器
 */
namespace app\controllers;
use app\models\ToolBase;
use Yii;

class ImageController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    public function actionMachine()
    {
        //收集图片 每一个月份为一个文件夹
        $dir='/uploads/machine/'.date('ym').'/'.date('d').'/';

        // 新建路径
         $imgdir['o'] = ToolBase::newDir($dir.'o/', Yii::getAlias('@webroot') );
         $imgdir['m'] = ToolBase::newDir($dir.'m/', Yii::getAlias('@webroot') );
         $imgdir['s'] = ToolBase::newDir($dir.'s/', Yii::getAlias('@webroot') );

        $path=pathinfo($_FILES['uploadfile']['name']);
        $type=strtolower($path['extension']);														//文件 扩展名
        $pic_type=array('jpg','png','jpeg','gif');
        // 判断图片格式
        if( !in_array( $type,$pic_type) )
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'输入图片格式不对']));

        $filename=uniqid( ToolBase::getSalt(7) ).'.'.$type;

        $MAX_SIZE = 5242880;
        if($_FILES['uploadfile']['size'] > $MAX_SIZE)
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'上传的图片大小超过了规定的5M']));
        if($_FILES['uploadfile']['size'] == 0)
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'上传的图片大小超过了规定的5M']));

        if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $imgdir['o'].$filename)){
            ToolBase::mt($imgdir['o'].$filename, $imgdir['m'].$filename,600,9000);
            ToolBase::mt($imgdir['o'].$filename, $imgdir['s'].$filename,150,1000);
            echo json_encode( ['status'=>1,'url'=>$dir.'s/'.$filename] );
        }
    }

    public function actionProduct()
    {
        //收集图片 每一个月份为一个文件夹
        $dir='/uploads/cover/'.date('ym').'/'.date('d').'/';

        // 新建路径
        $imgdir['o'] = ToolBase::newDir($dir.'o/', Yii::getAlias('@webroot') );
        $imgdir['m'] = ToolBase::newDir($dir.'m/', Yii::getAlias('@webroot') );
        $imgdir['s'] = ToolBase::newDir($dir.'s/', Yii::getAlias('@webroot') );

        $path=pathinfo($_FILES['uploadfile']['name']);
        $type=strtolower($path['extension']);														//文件 扩展名
        $pic_type=array('jpg','png','jpeg','gif');
        // 判断图片格式
        if( !in_array( $type,$pic_type) )
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'输入图片格式不对']));

        $filename=uniqid( ToolBase::getSalt(7) ).'.'.$type;

        $MAX_SIZE = 5242880;
        if($_FILES['uploadfile']['size'] > $MAX_SIZE)
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'上传的图片大小超过了规定的5M']));
        if($_FILES['uploadfile']['size'] == 0)
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'上传的图片大小超过了规定的5M']));

        if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $imgdir['o'].$filename)){
            ToolBase::mt($imgdir['o'].$filename, $imgdir['m'].$filename,600,9000);
            ToolBase::mt($imgdir['o'].$filename, $imgdir['s'].$filename,150,1000);
            echo json_encode( ['status'=>1,'url'=>$dir.'s/'.$filename] );
        }
    }

    public function actionQrcode()
    {
        //收集图片 每一个月份为一个文件夹
        $dir='/uploads/qrcode/'.date('ym').'/';

        // 新建路径
        $imgDir = ToolBase::newDir($dir, Yii::getAlias('@webroot') );

        $path=pathinfo($_FILES['uploadfile']['name']);
        $type=strtolower($path['extension']);														//文件 扩展名
        $pic_type=array('jpg','png','jpeg','gif');
        // 判断图片格式
        if( !in_array( $type,$pic_type) )
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'输入图片格式不对']));

        $filename=uniqid( ToolBase::getSalt(7) ).'.'.$type;

        $MAX_SIZE = 5242880;
        if($_FILES['uploadfile']['size'] > $MAX_SIZE)
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'上传的图片大小超过了规定的5M']));
        if($_FILES['uploadfile']['size'] == 0)
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'上传的图片大小超过了规定的5M']));

        ToolBase::mt($_FILES['uploadfile']['tmp_name'], $imgDir.$filename,700,9000);
        echo json_encode( ['status'=>1,'url'=>$dir.$filename] );
    }

//    抄表
    public function actionChargeSign()
    {
        //收集图片 每一个月份为一个文件夹
        $dir='/uploads/charge/'.date('ym').'/'.date('d').'/';

        // 新建路径
        $imgdir['o'] = ToolBase::newDir($dir.'o/', Yii::getAlias('@webroot') );
        $imgdir['m'] = ToolBase::newDir($dir.'m/', Yii::getAlias('@webroot') );

        $path=pathinfo($_FILES['uploadfile']['name']);
        $type=strtolower($path['extension']);														//文件 扩展名
        $pic_type=array('jpg','png','jpeg','gif');
        // 判断图片格式
        if( !in_array( $type,$pic_type) )
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'输入图片格式不对']));

        $filename=uniqid( ToolBase::getSalt(7) ).'.'.$type;

        $MAX_SIZE = 5242880;
        if($_FILES['uploadfile']['size'] > $MAX_SIZE)
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'上传的图片大小超过了规定的5M']));
        if($_FILES['uploadfile']['size'] == 0)
            Yii::$app->end( json_encode( ['status'=>0,'msg'=>'上传的图片大小超过了规定的5M']));

        if(move_uploaded_file($_FILES['uploadfile']['tmp_name'], $imgdir['o'].$filename)){
            ToolBase::mt($imgdir['o'].$filename, $imgdir['m'].$filename,600,9000);
            echo json_encode( ['status'=>1,'url'=>$dir.'m/'.$filename] );
        }
    }
}
