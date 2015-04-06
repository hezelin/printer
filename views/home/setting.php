<?php
/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '店铺设置';
?>

<div class="alert alert-info" role="alert">
    请设置微信店铺
</div>

    <?php
    use yii\bootstrap\Alert;
    if( Yii::$app->getSession()->hasFlash('error') ) {
        echo Alert::widget([
            'options' => [
                'class' => 'alert-error',///没有红色
            ],
            'body' => Yii::$app->getSession()->getFlash('error'),
        ]);
    }
    $form = ActiveForm::begin([
        'id' => 'setting-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ])
    ?>

<!--    --><?//= $form->field($model, 'name') ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('保存', ['class' => 'col-md-offset-1 col-md-2 btn btn-primary', 'name' => 'login-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end();?>





<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title>微曼科技</title>
    <link href='http://static.ptweixin.com/css/global.css?t=201409101111' rel='stylesheet' type='text/css'  />
    <link href='http://static.ptweixin.com/css/frame.css?t=201409101111' rel='stylesheet' type='text/css'  />
    <script type='text/javascript' src='http://static.ptweixin.com/js/jquery-1.7.2.min.js?t=201409101111' ></script>
    <script type='text/javascript' src='http://static.ptweixin.com/js/frame.js?t=201409101111' ></script>
</head>

<body>
<!--[if lte IE 9]><script type='text/javascript' src='http://static.ptweixin.com/js/plugin/jquery/jquery.watermark-1.3.js?t=201409101111' ></script>
<![endif]-->
<script language="javascript">$(document).ready(frame_obj.search_form_init); var session_id='pie73hdr59rfjjs0fhumkulds6';</script>
<div id="iframe_page">
    <div class="iframe_content">
        <link href='http://static.ptweixin.com/member/css/shop.css?t=201409101111' rel='stylesheet' type='text/css'  />
        <script type='text/javascript' src='http://static.ptweixin.com/member/js/shop.js?t=201409101111' ></script>
        <div class="r_nav">
            <ul>
                <li class="cur"><a href="./?m=shop&a=index">基本设置</a></li>
                <li class=""><a href="./?m=shop&a=skin">风格设置</a></li>
                <li class=""><a href="./?m=shop&a=home">首页设置</a></li>
                <li class=""><a href="./?m=shop&a=products">产品管理</a></li>
                <li class=""><a href="./?m=shop&a=orders">订单管理</a></li>

            </ul>
        </div>
        <link href='http://static.ptweixin.com/js/plugin/operamasks/operamasks-ui.css?t=201409101111' rel='stylesheet' type='text/css'  />
        <script type='text/javascript' src='http://static.ptweixin.com/js/plugin/operamasks/operamasks-ui.min.js?t=201409101111' ></script>
        <script language="javascript">$(document).ready(frame_obj.config_form_init);</script>
        <div class="r_con_config r_con_wrap">
            <form id="config_form">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50%" valign="top">
                            <h1><span class="fc_red">*</span> <strong>微商城名称</strong></h1>
                            <input type="text" class="input" name="ShopName" value="" maxlength="30" notnull />
                        </td>
                        <td width="50%" valign="top">
                            <h1><strong>需要物流</strong><span class="tips">（关闭后下订单无需填写收货地址）</span></h1>
                            <div class="input"><input type="checkbox" name="NeedShipping" value="1"  /><span class="tips">如果您提供的是本地化服务，无需物流，请关闭</span>div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <h1><strong>订单手机短信通知</strong> <input type="checkbox" name="SendSms" value="1"  /><span class="tips">启用（填接收短信的手机号）</span></h1>
                            <input type="text" class="input" name="SendSmsMobilePhone" value="" maxlength="11" />
                        </td>
                        <td valign="top">
                            <h1><strong>产品评论</strong> <input type="checkbox" name="NeedReview" value="1"  /><span class="tips">启用</span></h1>
                            <div class="input"><input type="checkbox" name="ReviewPass" value="1"  /><span class="tips">如果评论需要审核后才显示，请开启本选项</span></div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <h1><strong>下订单获得积分</strong> <input type="checkbox" name="GetIntegral" value="1"  /><span class="tips">启用（每元可获得的积分数）</span></h1>
                            <input type="text" class="input" name="GetIntegralValue" value="0.00" maxlength="5" />
                        </td>
                        <td valign="top">
                            <h1><strong>分配订单到子门店系统</strong></h1>
                            <div class="input"><input type="checkbox" name="NeedStores" value="1"  /><span class="tips">如果您需要让各家门店分别管理订单，请开启</span></div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <h1><strong>开启库存</strong> <input type="checkbox" name="NeedStock" value="1"  /><span class="tips">开启库存后购买数量不能大于库存量</span></h1>
                            <div class="input"><input type="checkbox" name="AutoStock" value="1"  /><span class="tips">如果商品库存为0时自动下架</span></div>
                        </td>
                        <td valign="top">
                            <h1><strong>搜索</strong><span class="tips">（<input type="radio" name="SearchStyle" value="0" checked="checked" />关闭&nbsp;&nbsp;<input type="radio" name="SearchStyle" value="1"  />固定&nbsp;&nbsp;<input type="radio" name="SearchStyle" value="2"  />浮动）</span></h1>
                            <input type="text" class="input" name="SearchInit" value="这是一个神奇的搜索"  />
                        </td>
                    </tr>
                </table>
                <table align="center" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <h1><strong>触发信息设置</strong></h1>
                            <div class="reply_msg">
                                <div class="m_left">
                                    <span class="fc_red">*</span> 触发关键词<span class="tips_key">（有多个关键词请用空格隔开）</span><br />
                                    <input type="text" class="input" name="ReplyKeyword" value="商城" maxlength="100" notnull /><br /><br /><br />
                                    <span class="fc_red">*</span> 匹配模式<br />
                                    <div class="input"><input type="radio" name="PatternMethod" value="0" checked />精确匹配<span class="tips">（输入的文字和此关键词一样才触发）</span></div>
                                    <div class="input"><input type="radio" name="PatternMethod" value="1"  />模糊匹配<span class="tips">（输入的文字包含此关键词就触发）</span></div><br /><br />
                                    <span class="fc_red">*</span> 图文消息标题<br />
                                    <input type="text" class="input" name="ReplyTitle" value="商城" maxlength="100" notnull /><br /><br /><br />
                                    <span class="fc_red">*</span> 图文消息简介<br />
                                    <textarea name="BriefDescription"></textarea>
                                </div>
                                <div class="m_right">
                                    <span class="fc_red">*</span> 图文消息封面<span class="tips">（图片尺寸建议：640*360px）</span><br />
                                    <div class="file"><input name="ReplyImgUpload" id="ReplyImgUpload" type="file" /></div><br />
                                    <div class="img" id="ReplyImgDetail"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <input type="hidden" name="ReplyImgPath" value="http://static.ptweixin.com/api/images/global/cover/shop.jpg" />
                        </td>
                    </tr>
                </table>		<div class="submit"><input type="submit" name="submit_button" value="提交保存" /></div>
                <input type="hidden" name="do_action" value="shop.config">
            </form>
        </div>	</div>
</div>
</body>
</html>