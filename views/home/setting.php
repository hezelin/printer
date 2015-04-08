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

    $form = ActiveForm::begin([
        'id' => 'setting-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ])
    ?>

    <?= $form->field($model, 'webname')->textInput() ?>
    <?= $form->field($model, 'weixinid')->textInput() ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'description')->textarea() ?>

    <?= $form->field($model, 'status')->radioList([1=>'启用',0=>'关闭']) ?>
    <?= Html::hiddenInput('RegisterForm[area]','',['id'=>'city-region-id'])?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('提交保存', ['class' => 'col-md-2 btn btn-primary', 'name' => 'setting-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>






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
<script language="javascript">$(document).ready(frame_obj.search_form_init); var session_id='abe1qju6k52gkqs7tgbl8rqk73';</script>
<div id="iframe_page">
    <div class="iframe_content">

        <link href='http://static.ptweixin.com/member/css/web.css?t=201409101111' rel='stylesheet' type='text/css'  />
        <script type='text/javascript' src='http://static.ptweixin.com/member/js/web.js?t=201409101111' ></script>
        <div class="r_nav">
            <ul>
                <li class="cur"><a href="./?m=web&a=index">基本设置</a></li>
                <li class=""><a href="./?m=web&a=skin">风格设置</a></li>
                <li class=""><a href="./?m=web&a=home">首页设置</a></li>
                <li class=""><a href="./?m=web&a=column">栏目管理</a></li>
                <li class=""><a href="./?m=web&a=lbs">一键导航</a></li>
            </ul>
        </div>
        <link href='http://static.ptweixin.com/js/plugin/operamasks/operamasks-ui.css?t=201409101111' rel='stylesheet' type='text/css'  />
        <script type='text/javascript' src='http://static.ptweixin.com/js/plugin/operamasks/operamasks-ui.min.js?t=201409101111' ></script>
        <script language="javascript">
            $(document).ready(function(){
                web_obj.web_config_init();
                frame_obj.config_form_init();
            });
        </script>
        <div class="r_con_config r_con_wrap">
            <form id="config_form">
                <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="50%" valign="top">
                            <h1><span class="fc_red">*</span> <strong>微官网名称</strong></h1>
                            <input type="text" class="input" name="SiteName" value="" maxlength="30" notnull />
                        </td>
                        <td width="50%" valign="top">
                            <h1><strong>一键拨号</strong> <input type="checkbox" name="CallEnable" value="1" checked /><span class="tips">启用</span></h1>
                            <input type="text" class="input" name="CallPhoneNumber" value="" maxlength="20" />
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <h1><strong>动画效果</strong></h1>
                            <div class="input">
                                <input type="radio" value="0" name="Animation" checked />无
                                <input type="radio" value="1" name="Animation"  />雪花
                                <input type="radio" value="2" name="Animation"  />烟花
                            </div>
                        </td>
                        <td valign="top">
                            <h1><strong>分享图标</strong></h1>
                            <div class="input"><input type="checkbox" name="ArticleShareBtn" value="1" checked /><span class="tips">详细页是否显示分享图标</span></div>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top">
                            <h1><strong>引导页</strong></h1>
                            <div class="input">
                                <input type="radio" value="0" name="PagesShow" checked />无
                                <input type="radio" value="1" name="PagesShow"  />马赛克
                                <input type="radio" value="2" name="PagesShow"  />淡出
                                <input type="radio" value="3" name="PagesShow"  />开门
                            </div>
                            <div class="rows"><span class="lbar">播放时间(秒):</span><span class="rbar"><input type="text" class="input w70" name="ShowTime" value="3" maxlength="10" /></span>
                                <div class="clear"></div>
                            </div>
                            <div class="pages_pic">
                                <span class="up_input"><input name="PagesPicUpload" id="PagesPicUpload" type="file" /></span>
                                <span class="tips">建议尺寸:640*1010px</span>
                                <div class="clear"></div>
                            </div>
                            <div id="PagesPicDetail"></div>
                            <input type="hidden" name="PagesPic" value="" />
                        </td>
                        <td valign="top">
                            <h1><strong>背景音乐</strong><span class="tips">（填写音乐链接地址或上传音乐文件）</span></h1>
                            <input type="text" class="input" name="MusicPath" value="" maxlength="800" />
                            <div class="up_mp3">
                                <span class="up_input"><input name="MusicUpload" id="MusicUpload" type="file" /></span>
                                <span class="tips">500KB以内，mp3格式</span>
                            </div>
                            <div class="blank9"></div>
                            <h1><strong>搜索</strong> <input type="checkbox" name="NeedSearch" value="1"  /><span class="tips">启用</span></h1>
                            <input type="text" class="input" name="SearchInit" value="这是一个神奇的搜索" maxlength="20" />
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
                                    <input type="text" class="input" name="ReplyKeyword" value="官网" maxlength="100" notnull /><br /><br /><br />
                                    <span class="fc_red">*</span> 匹配模式<br />
                                    <div class="input"><input type="radio" name="PatternMethod" value="0" checked />精确匹配<span class="tips">（输入的文字和此关键词一样才触发）</span></div>
                                    <div class="input"><input type="radio" name="PatternMethod" value="1"  />模糊匹配<span class="tips">（输入的文字包含此关键词就触发）</span></div><br /><br />
                                    <span class="fc_red">*</span> 图文消息标题<br />
                                    <input type="text" class="input" name="ReplyTitle" value="官网" maxlength="100" notnull /><br /><br /><br />
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
                            <input type="hidden" name="ReplyImgPath" value="http://static.ptweixin.com/api/images/global/cover/web.jpg" />
                        </td>
                    </tr>
                </table>		<div class="submit"><input type="submit" name="submit_button" value="提交保存" /></div>
                <input type="hidden" name="do_action" value="web.config">
            </form>
        </div>	</div>
</div>
</body>
</html>