<?php
    $this->title = '店铺装修';
    use yii\helpers\Html;
    use dosamigos\fileupload\FileUploadUI;
    use yii\bootstrap\Alert;
    $this->registerJsFile("/js/weixin/jquery.min.js",['position' => \yii\web\View::POS_HEAD]);
    $this->registerJsFile("/js/myjs.js");
?>

<style>
    .textedit {
        padding-right: 18px;
        background: url('/images/pencil.png') no-repeat right;
        background-size: 16px;
    }
</style>

<!--Ajax加载提示-->
<div id="loading_tip" style="background-color:orange; display:none">提交请求...&nbsp;&nbsp;&nbsp;<img src="/images/loading.gif"></div>
<div id="success_tip" style="background-color:rgb(13,214,12); display:none;">操作成功</div>
<div id="error_tip" style="background-color:red; display:none"></div>

<h3>首页轮播图片设置</h3>
<hr>
<?=Alert::widget([
    'options' => [
        'class' => 'alert-info',
        'style' => 'color:gray; font-size: 12px',
    ],
    'body' => '<strong>温馨提示</strong>：为保证显示质量，请上传长宽比例为2:1的图片。',
    ])
?>
<!--上传图片插件-->
<?= FileUploadUI::widget([
    'model' => $model,
    'attribute' => 'image',
    'url' => ['home/receiveimage', 'weixinid' => $_GET['id']],
    'gallery' => false,
    'fieldOptions' => [
        'accept' => 'image/*',
    ],
    'clientOptions' => [
        'maxFileSize' => 2000000,
        //'maxNumberOfFiles' => 5,  //这里是同时存在页面的数量
    ],
    // 打印日志
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                    $("#newadd").show();
                                    $("#newtable").show();
                                    console.log(e);
                                    console.log(data);
                                }',
        'fileuploadfail' => 'function(e, data) {
                                    console.log(e);
                                    console.log(data);
                                }',
        'fileuploaddestroy' => 'function(e, data) {
                                    //还得添加删除按钮事件$(this).find(tr).remove();
                                }',
    ],
]);
?>

<hr>
<h4>已添加轮播图</h4>
    <table role="presentation" class="table table-striped">
        <tbody class="files">
        <tr align="center">
            <td>
                轮播图预览
            </td>
            <td>
                标题
            </td>
            <td>
                链接
            </td>
            <td>
                操作
            </td>
        </tr>
<?php
    foreach ($carousel as $onecarousel) {
        if(is_file($onecarousel['imgurl'])){
            $imagepath = '/'.$onecarousel['imgurl'];
?>
        <tr class="template-download fade in">
            <td>
            <span class="preview">

                    <a href="<?php echo $imagepath;?>" target="_blank" title="<?php echo $onecarousel['imgurl'];?>" data-gallery="">
                        <img src="<?php echo $imagepath;?>" title="<?php echo $onecarousel['title'];?>" style="max-height: 100px"/></a>

            </span>
            </td>
            <td>
                <p class="name">

                    <span class="textedit" data-tdtype="edit" data-id="<?=$onecarousel['id'] ?>" data-field="title" data-unique="0"><?php echo trim($onecarousel['title'])?$onecarousel['title']:"点击设置";?></span>

                </p>

            </td>
            <td>
                <span class="textedit" data-tdtype="edit" data-id="<?=$onecarousel['id'] ?>" data-field="link" data-unique="0"><?php echo trim($onecarousel['link'])?$onecarousel['link']:"点击设置";?></span>
            </td>
            <td>

                <button class="btn btn-danger delete" data-id="<?=$onecarousel['id'] ?>" data-type="DELETE" data-url="home/delimg" onclick="deleteimg(this)">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>删除</span>
                </button>
                <input name="delete" value="<?=$onecarousel['id'] ?>" class="toggle" type="checkbox">

            </td>
        </tr>
<?php }} ?>
        </tbody>
    </table>
