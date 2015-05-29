<?php
/**
 * 微信用户
 */

namespace app\models;
use Yii;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;

class WxUser extends WxBase {

    /*
     * 获取用户资料并发数量
     */
    public $multiNum = 100;
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
     * ?access_token=ACCESS_TOKEN&next_openid=NEXT_OPENID
     */
    public function pullUser($nextOpenid = false)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get';
        $param = [
            'access_token'=>$this->accessToken(),
        ];
        if($nextOpenid)
            $param['next_openid'] = $nextOpenid;

        $curl = new Curl();
        $data = $curl->getJson($url,$param);
        if( isset($data['errmsg']) )
            throw new BadRequestHttpException($data['errmsg']);

        if(isset($data['data']['openid'])){
            $n = (int)(count($data['data']['openid'])/$this->multiNum);
            for($i=0;$i<=$n;$i++) {
                $sliceOpenid = array_slice($data['data']['openid'], $i * $this->multiNum, $this->multiNum, true);
                $urls = $this->dealUrl($sliceOpenid);
                $this->saveUser( $curl->getMulti($urls) );
            }

        }

        if($data['total'] > 10000 && $data['next_openid'])
            $this->pullUser($data['next_openid']);

        return true;
    }

    /*
     * 每次并发获取 100条数据
     */
    private function dealUrl($openids)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info';
        $tmp = [];
        foreach($openids as $id){
            $tmp[$id] = $url . '?' . http_build_query(['access_token'=>$this->accessToken(), 'openid'=>$id, 'lang'=>'zh_CN']);
        }
        return $tmp;
    }

    /*
     * 保存用户资料
     * 拼接 sql 保存用户资料
     */
    private function saveUser($info)
    {
        $sql = 'insert into tbl_user_wechat (`wx_id`,`openid`,`nickname`,`sex`,`city`,`country`,`province`,`language`,`headimgurl`,`subscribe_time`,`subscribe`) values ';
        $val = '';
        $id = $this->id;
        foreach($info as $user)
        {
            $in = json_decode($user,true);
            $val .= "({$id},'{$in['openid']}','{$in['nickname']}',{$in['sex']},'{$in['city']}','{$in['country']}','{$in['province']}','{$in['language']}','{$in['headimgurl']}',{$in['subscribe_time']},{$in['subscribe']}),";
        }

        $sql .= substr($val,0,-1) . ' ON DUPLICATE KEY UPDATE nickname=values(nickname),sex=values(sex),city=values(city),country=values(country),province=values(province),language=values(language),headimgurl=values(headimgurl),subscribe_time=values(subscribe_time),subscribe=values(subscribe)';

        return Yii::$app->db->createCommand($sql)->execute();
    }
}