<?php

namespace app\models;

use Yii;


/**
 * 微信消息 模型
 */
class WxChat
{

    //$_GET参数
    public $signature;
    public $timestamp;
    public $nonce;
    public $echostr;

    public $id;         // 微信id
    //
    public $token;
    public $debug = false;
    public $msg = array();
    public $setFlag = false;

    /**
     * 赋值 微信get 上来的参数
     * signature/timestamp/nonce
     * 赋值  url 传递上来的 id
     */
    public function __construct($params)
    {
        foreach ($params as $k1 => $v1)
        {
            if (property_exists($this, $k1))
                $this->$k1 = $v1;
        }

        $this->token = md5( $this->id . Yii::$app->params['wxTokenSalt'] );
    }

    /**
     * 验证 微信url,验证成功 原样返回 echostr
     */
    public function valid()
    {
        if($this->checkSignature()){
            echo $this->echostr;
            exit;
        }
    }

    /**
     * 获得用户发过来的消息（消息内容和消息类型  ）
     * <xml>
    <ToUserName><![CDATA[toUser]]></ToUserName>
    <FromUserName><![CDATA[fromUser]]></FromUserName>
    <CreateTime>1348831860</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[this is a test]]></Content>
    <MsgId>1234567890123456</MsgId>
    </xml>
     */
    public function init()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)) {
            $this->msg = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        }

    }

    /**
     * 回复事件消息
     *
     * @access public
     * @return void
     */
    public function makeEvent()
    {
        $createTime = time();
        $funcFlag = $this->setFlag ? 1 : 0;
        $textTpl = "<xml>
            <ToUserName><![CDATA[{$this->msg->FromUserName}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg->ToUserName}]]></FromUserName>
            <CreateTime>{$createTime}</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            <FuncFlag>%s</FuncFlag>
            </xml>";

        return sprintf($textTpl,'你的openId = '.$this->msg->FromUserName,$funcFlag);
    }

    /**
     * 回复文本消息
     *
     * @param string $text
     * @access public
     * @return void
     */
    public function makeText($text='')
    {
        $createTime = time();
        $textTpl = "<xml>
            <ToUserName><![CDATA[{$this->msg->FromUserName}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg->ToUserName}]]></FromUserName>
            <CreateTime>{$createTime}</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>";
        return sprintf($textTpl,$text);
    }

    /**
     * 根据数组参数回复图文消息
     *
     * @param array $newsData
     * @access public
     * @return void
     */
    public function makeNews($newsData=array())
    {
        $createTime = time();
        $funcFlag = $this->setFlag ? 1 : 0;
        $newTplHeader = "<xml>
            <ToUserName><![CDATA[{$this->msg->FromUserName}]]></ToUserName>
            <FromUserName><![CDATA[{$this->msg->ToUserName}]]></FromUserName>
            <CreateTime>{$createTime}</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <ArticleCount>%s</ArticleCount><Articles>";
        $newTplItem = "<item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>";
        $newTplFoot = "</Articles>
            <FuncFlag>%s</FuncFlag>
            </xml>";
        $content = '';
        $itemsCount = count($newsData['items']);
        //微信公众平台图文回复的消息一次最多10条
        $itemsCount = $itemsCount < 10 ? $itemsCount : 10;
        if ($itemsCount) {
            foreach ($newsData['items'] as $key => $item) {
                if ($key<=9) {
                    $content .= sprintf($newTplItem,$item['title'],$item['description'],$item['picurl'],$item['url']);
                }
            }
        }
        $header = sprintf($newTplHeader,$itemsCount);
        $footer = sprintf($newTplFoot,$funcFlag);
        return $header . $content . $footer;
    }

    /**
     * 回复、应答
     */
    public function reply($data)
    {
        echo $data;
    }

    /**
     * 验证微信 签名
     */
    private function checkSignature()
    {
        $tmpArr = array($this->token, $this->timestamp, $this->nonce);
        sort($tmpArr,SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $this->signature ){
            return true;
        }else{
            return false;
        }
    }

}