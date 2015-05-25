<?php
/**
 * 微信资源
 */

namespace app\models;
use Yii;

class WxMedia extends WxBase {

    /*
     * 获取资源并且保存
     * 保存方式，images,voice
     */
    public function getImage($media_id)
    {
        $curl = new Curl();
        $media = $curl->get('http://file.api.weixin.qq.com/cgi-bin/media/get',[
            'access_token'=>$this->accessToken(),
            'media_id'=>$media_id
        ]);

        $dateDir = date('ym',time()).'/'.date('d',time());
        $dir = ToolBase::newDir('images/' . $dateDir.'/o', Yii::getAlias('@webroot'));
        $dirSmall = ToolBase::newDir('images/' . $dateDir.'/m', Yii::getAlias('@webroot'));
        $file = ToolBase::getSalt(7) . uniqid() . '.jpg';
        file_put_contents($dir . '/' . $file, $media);
        ToolBase::mt($dir . '/' . $file,$dirSmall . '/' . $file,600,2000);
        return '/images/' . $dateDir . '/m/' . $file;
    }

    /*
     * 获取批量
     * array $media_ids
     */
    public function getImages($media_ids)
    {
        $imgUrl = [];
        foreach($media_ids as $media_id)
            $imgUrl[] = $this->getImage($media_id);
        return $imgUrl;
    }
} 