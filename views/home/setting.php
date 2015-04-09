<?php
/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = '店铺设置';
?>

<div class="alert alert-info" role="alert">
    1. 快捷导航菜单将包含4项快捷菜单按钮，启用一键拨号时请设定正确的电话号码。<br/>
    2. 设置触发信息后，当用户输入匹配的关键词后公众号将自动回复设定的单图文消息，其链接为微官网首页。<br/>
    3. <span class="red">精确匹配</span>意为用户输入的文字与关键词完全一致才触发，<span class="red">模糊匹配</span>则为输入的文字中包含关键词就会触发。
</div>
<!--*******************************************************************-->
<hr>
    <h4>&nbsp;店铺基础设置</h4>
<hr>
    <?php
    $form = ActiveForm::begin([
        'id' => 'setting-form',
        'options' => ['id' => 'storesetting-form','class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ])
    ?>

    <?= Html::tag('div',
        Html::label('微信绑定','websettingform-wechat',['class' => 'col-lg-2 control-label']).
        Html::tag('div',Yii::$app->session['wechat']['name'],['id' => 'websettingform-wechat', 'class' => 'col-lg-5', 'style' => 'padding-top: 7px;']),
        ['class' => 'form-group']) ?>
    <?= $form->field($model, 'storename')->textInput(['value'=>$model->storename?$model->storename:Yii::$app->session['wechat']['name']]) ?>
<?php
//= Html::tag('div',
//        Html::label('快捷导航菜单','websettingform-menu',['class' => 'col-lg-2 control-label']).$form->field($model, 'showmenu')->checkbox(['style'=>'margin-left:7px']),
//        ['class' => 'form-group'])
?>
    <?= $form->field($model, 'showmenu')->radioList(['1'=>'展示','0'=>'隐藏']) ?>
    <?= $form->field($model, 'tel')->textInput() ?>
    <?= $form->field($model, 'status')->radioList(['1'=>'启用','0'=>'关闭']) ?>

    <div class="form-group">
        <div class="h3 col-lg-offset-2 col-lg-9">
            <?= Html::submitButton('提交保存', ['class' => 'col-md-2 btn btn-primary', 'name' => 'setting-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

<!--*******************************************************************-->
<hr>
    <h4>&nbsp;触发信息设置</h4>
<hr>
    <?php
    $form2 = ActiveForm::begin([
        'id' => 'setting-form',
        'options' => ['id' => 'triggermessage-form','class' => 'form-horizontal','enctype' => 'multipart/form-data', 'action'=>'/home/triggermessage'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-5\">{input}</div>\n<div class=\"col-lg-5\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-2 control-label'],
        ],
    ])
    ?>
    <?= $form2->field($model2, 'keyword')->textInput() ?>
    <?= $form2->field($model2, 'matchmode')->radioList([1=>'精确匹配',2=>'模糊匹配']) ?>
    <?= $form2->field($model2, 'title')->textInput() ?>
    <?= $form2->field($model2, 'description')->textarea() ?>
    <?= $form2->field($model2, 'imagefile')->fileInput() ?>
    <img class="col-lg-offset-2" src="/<?=is_file($model2->imageurl)?$model2->imageurl:'images/home.jpg' ?>" style="border:1px solid #ccc;max-width:350px">
    <!--$image = 'http://files.leiphone.com/uploads/01-5/-2/01-58-24-14.png';-->
    <?= $form2->field($model2, 'status')->radioList(['1'=>'是','0'=>'否']) ?>

    <div class="form-group">
        <div class="h3 col-lg-offset-2 col-lg-9">
            <?= Html::submitButton('提交保存', ['class' => 'col-md-2 btn btn-primary', 'name' => 'setting-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>






<!--<!DOCTYPE HTML>-->
<!--<html>-->
<!--<head>-->
<!--    <meta charset="utf-8">-->
<!--    <title>微曼科技</title>-->
<!--    <!--<link href='http://static.ptweixin.com/css/global.css?t=201409101111' rel='stylesheet' type='text/css'  />-->-->
<!--    <link href='http://static.ptweixin.com/css/frame.css?t=201409101111' rel='stylesheet' type='text/css'  />-->
<!--    <script type='text/javascript' src='http://static.ptweixin.com/js/jquery-1.7.2.min.js?t=201409101111' ></script>-->
<!--    <script type='text/javascript' src='http://static.ptweixin.com/js/frame.js?t=201409101111' ></script>-->
<!--</head>-->
<!---->
<!--<body>-->
<!--<!--[if lte IE 9]><script type='text/javascript' src='http://static.ptweixin.com/js/plugin/jquery/jquery.watermark-1.3.js?t=201409101111' ></script>-->
<!--<![endif]-->-->
<!--<script language="javascript">$(document).ready(frame_obj.search_form_init); var session_id='abe1qju6k52gkqs7tgbl8rqk73';</script>-->
<!--<div id="iframe_page">-->
<!--    <div class="iframe_content">-->
<!---->
<!--        <link href='http://static.ptweixin.com/member/css/web.css?t=201409101111' rel='stylesheet' type='text/css'  />-->
<!--        <script type='text/javascript' src='http://static.ptweixin.com/member/js/web.js?t=201409101111' ></script>-->
<!--        <div class="r_nav">-->
<!--            <ul>-->
<!--                <li class="cur"><a href="./?m=web&a=index">基本设置</a></li>-->
<!--                <li class=""><a href="./?m=web&a=skin">风格设置</a></li>-->
<!--                <li class=""><a href="./?m=web&a=home">首页设置</a></li>-->
<!--                <li class=""><a href="./?m=web&a=column">栏目管理</a></li>-->
<!--                <li class=""><a href="./?m=web&a=lbs">一键导航</a></li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <link href='http://static.ptweixin.com/js/plugin/operamasks/operamasks-ui.css?t=201409101111' rel='stylesheet' type='text/css'  />-->
<!--        <script type='text/javascript' src='http://static.ptweixin.com/js/plugin/operamasks/operamasks-ui.min.js?t=201409101111' ></script>-->
<!--        <script language="javascript">-->
<!--            $(document).ready(function(){-->
<!--                web_obj.web_config_init();-->
<!--                frame_obj.config_form_init();-->
<!--            });-->
<!--        </script>-->
<!--        <div class="r_con_config r_con_wrap">-->
<!--            <form id="config_form">-->
<!--                <table border="0" cellpadding="0" cellspacing="0">-->
<!--                    <tr>-->
<!--                        <td width="50%" valign="top">-->
<!--                            <h1><span class="fc_red">*</span> <strong>微官网名称</strong></h1>-->
<!--                            <input type="text" class="input" name="SiteName" value="" maxlength="30" notnull />-->
<!--                        </td>-->
<!--                        <td width="50%" valign="top">-->
<!--                            <h1><strong>一键拨号</strong> <input type="checkbox" name="CallEnable" value="1" checked /><span class="tips">启用</span></h1>-->
<!--                            <input type="text" class="input" name="CallPhoneNumber" value="" maxlength="20" />-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td valign="top">-->
<!--                            <h1><strong>动画效果</strong></h1>-->
<!--                            <div class="input">-->
<!--                                <input type="radio" value="0" name="Animation" checked />无-->
<!--                                <input type="radio" value="1" name="Animation"  />雪花-->
<!--                                <input type="radio" value="2" name="Animation"  />烟花-->
<!--                            </div>-->
<!--                        </td>-->
<!--                        <td valign="top">-->
<!--                            <h1><strong>分享图标</strong></h1>-->
<!--                            <div class="input"><input type="checkbox" name="ArticleShareBtn" value="1" checked /><span class="tips">详细页是否显示分享图标</span></div>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                    <tr>-->
<!--                        <td valign="top">-->
<!--                            <h1><strong>引导页</strong></h1>-->
<!--                            <div class="input">-->
<!--                                <input type="radio" value="0" name="PagesShow" checked />无-->
<!--                                <input type="radio" value="1" name="PagesShow"  />马赛克-->
<!--                                <input type="radio" value="2" name="PagesShow"  />淡出-->
<!--                                <input type="radio" value="3" name="PagesShow"  />开门-->
<!--                            </div>-->
<!--                            <div class="rows"><span class="lbar">播放时间(秒):</span><span class="rbar"><input type="text" class="input w70" name="ShowTime" value="3" maxlength="10" /></span>-->
<!--                                <div class="clear"></div>-->
<!--                            </div>-->
<!--                            <div class="pages_pic">-->
<!--                                <span class="up_input"><input name="PagesPicUpload" id="PagesPicUpload" type="file" /></span>-->
<!--                                <span class="tips">建议尺寸:640*1010px</span>-->
<!--                                <div class="clear"></div>-->
<!--                            </div>-->
<!--                            <div id="PagesPicDetail"></div>-->
<!--                            <input type="hidden" name="PagesPic" value="" />-->
<!--                        </td>-->
<!--                        <td valign="top">-->
<!--                            <h1><strong>背景音乐</strong><span class="tips">（填写音乐链接地址或上传音乐文件）</span></h1>-->
<!--                            <input type="text" class="input" name="MusicPath" value="" maxlength="800" />-->
<!--                            <div class="up_mp3">-->
<!--                                <span class="up_input"><input name="MusicUpload" id="MusicUpload" type="file" /></span>-->
<!--                                <span class="tips">500KB以内，mp3格式</span>-->
<!--                            </div>-->
<!--                            <div class="blank9"></div>-->
<!--                            <h1><strong>搜索</strong> <input type="checkbox" name="NeedSearch" value="1"  /><span class="tips">启用</span></h1>-->
<!--                            <input type="text" class="input" name="SearchInit" value="这是一个神奇的搜索" maxlength="20" />-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                </table>-->
<!--                <table align="center" border="0" cellpadding="0" cellspacing="0">-->
<!--                    <tr>-->
<!--                        <td>-->
<!--                            <h1><strong>触发信息设置</strong></h1>-->
<!--                            <div class="reply_msg">-->
<!--                                <div class="m_left">-->
<!--                                    <span class="fc_red">*</span> 触发关键词<span class="tips_key">（有多个关键词请用空格隔开）</span><br />-->
<!--                                    <input type="text" class="input" name="ReplyKeyword" value="官网" maxlength="100" notnull /><br /><br /><br />-->
<!--                                    <span class="fc_red">*</span> 匹配模式<br />-->
<!--                                    <div class="input"><input type="radio" name="PatternMethod" value="0" checked />精确匹配<span class="tips">（输入的文字和此关键词一样才触发）</span></div>-->
<!--                                    <div class="input"><input type="radio" name="PatternMethod" value="1"  />模糊匹配<span class="tips">（输入的文字包含此关键词就触发）</span></div><br /><br />-->
<!--                                    <span class="fc_red">*</span> 图文消息标题<br />-->
<!--                                    <input type="text" class="input" name="ReplyTitle" value="官网" maxlength="100" notnull /><br /><br /><br />-->
<!--                                    <span class="fc_red">*</span> 图文消息简介<br />-->
<!--                                    <textarea name="BriefDescription"></textarea>-->
<!--                                </div>-->
<!--                                <div class="m_right">-->
<!--                                    <span class="fc_red">*</span> 图文消息封面<span class="tips">（图片尺寸建议：640*360px）</span><br />-->
<!--                                    <div class="file"><input name="ReplyImgUpload" id="ReplyImgUpload" type="file" /></div><br />-->
<!--                                    <div class="img" id="ReplyImgDetail"></div>-->
<!--                                </div>-->
<!--                                <div class="clear"></div>-->
<!--                            </div>-->
<!--                            <input type="hidden" name="ReplyImgPath" value="http://static.ptweixin.com/api/images/global/cover/web.jpg" />-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                </table>		<div class="submit"><input type="submit" name="submit_button" value="提交保存" /></div>-->
<!--                <input type="hidden" name="do_action" value="web.config">-->
<!--            </form>-->
<!--        </div>	</div>-->
<!--</div>-->
<!--</body>-->
<!--</html>-->