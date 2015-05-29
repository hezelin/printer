<?php
/**
 * 微信用户
 */

namespace app\models;
use Yii;
use yii\helpers\Url;

class WxUser extends WxBase {

    /*
     * 根据openid 获取关注者资料
     * 当用户关注成功之后，拉取资料
     */
    public function getUser($openid = false,$isSave = false)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info';
        $params = [
            'access_token'=>$this->accessToken(),
            'openid'=>$openid? (string)$openid: self::openId($this->id),
            'lang'=>'zh_CN'
        ];

        $curl = new Curl();
        $userinfo = $curl->getJson($url,$params);

        if($isSave && isset($userinfo['subscribe']) && $userinfo['subscribe']){

            $user = new TblUserWechat();
            $user->wx_id = $this->id;
            $user->attributes = $userinfo;
            if(!$user->save())
                return ToolBase::arrayToString( $user->errors );
        }
        return $userinfo;
    }

    /*
     * 删除 tbl_user_wechat 用户
     */
    public function delUser($openid)
    {
        $user = TblUserWechat::findOne(['wx_id'=>$this->id,'openid'=>(string)$openid]);
        if($user)
            return $user->delete();
        return true;
    }

    /*
     * $id 公众号id,$openid 用户$openid
     */
    public function updateUser($openid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info';
        $params = [
            'access_token'=>$this->accessToken(),
            'openid'=>$openid? (string)$openid: self::openId($this->id),
            'lang'=>'zh_CN'
        ];

        $curl = new Curl();
        $userinfo = $curl->getJson($url,$params);

        if(isset($userinfo['openid'])){
            $model = TblUserWechat::findOne(['wx_id'=>$this->id,'openid'=>$openid]);
            $model->attributes = $userinfo;
            return $model->save();
        }
        return false;

    }

    /*
     * 下拉用户列表
     */
    public function pullUser()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=ACCESS_TOKEN&next_openid=NEXT_OPENID';


    }
} 