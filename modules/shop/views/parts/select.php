<?php
use yii\helpers\Url;
use app\models\ConfigBase;
$this->title = '维修列表';
Yii::$app->params['layoutBottomHeight'] = 40;

?>
<style type="text/css">
    .h-list {
        position: relative;
        padding: 0;
        margin: 0;
        background-color: #EEEEEE;
    }
    .h-list a {
        display: block;
    }
    .h-list ul > li {
        background-color: #fff;
        padding: 5px;
        height: 140px;
        float: left;
        width: 100%;
        position: relative;
        border: 1px dashed #EEEEEE;
    }
    .h-list .li-cover {
        width: 40%;
        height: 120px;
        float: left;
        text-align: center;
        position: relative;
        vertical-align: middle;
    }
    .h-list .li-cover-img {
        max-width: 100%;
        max-height: 120px;
    }
    .h-list .li-name {
        width: 56%;
        white-space: nowrap;
        text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        overflow: hidden;
        margin-top: 4px;
        font-family: "microsoft yahei";
        color: #4e6fad;
        font-size: 16px;
        font-weight: 600;
    }
    .h-list .li-row {
        height: 30px;
        line-height: 30px;
        float: left;
        width: 56%;
        margin-left: 4%;
    }
    .h-list .li-row-small {
        height: 24px;
        line-height: 24px;
        float: left;
        font-size: 12px;
        width: 56%;
        white-space: nowrap;
        text-overflow: ellipsis;
        -o-text-overflow: ellipsis;
        overflow: hidden;
        margin-left: 4%;
    }
    .h-list .li-label {
        font-size: 12px;
        color: #999999;
        width: 15%;
    }
    .h-list .li-yan {
        vertical-align: middle;
        color: #c40000;
        font-size: 18px;
        font-family: Arial;
        font-style: normal;
        -webkit-font-smoothing: antialiased;
    }
    .h-list .li-price {
        vertical-align: middle;
        color: #c40000;
        font-weight: bolder;
        font-family: Arial;
        font-size: 24px;
        -webkit-font-smoothing: antialiased;
    }
    .h-list .li-func {
        line-height: 24px;
        font-size: 14px;
        color: #333333;
        height: 48px;
        margin-top: 4px;
        overflow: hidden;
        float: left;
    }
    .h-list .li-time {
        vertical-align: middle;
        font-family: Arial;
        font-size: 16px;
        -webkit-font-smoothing: antialiased;
    }
    .h-list .m-apply {
        height: 35px;
        line-height: 32px;
        background-image: -moz-linear-gradient(top, #ffffff, #e1e1e1);
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ffffff), color-stop(1, #e1e1e1));
        background-image: -o-linear-gradient(top, #ffffff, #e1e1e1);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#e1e1e1', GradientType='0');
        text-align: center;
        font-size: 18px;
        color: #000000;
        width: 100%;
        float: left;
        border-top: 1px solid #e1e1e1;
        border-bottom: 1px solid #cfcfcf;
        color: #999999;
    }
    .h-list .m-apply-60 {
        height: 35px;
        line-height: 32px;
        background-image: -moz-linear-gradient(top, #ffffff, #e1e1e1);
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ffffff), color-stop(1, #e1e1e1));
        background-image: -o-linear-gradient(top, #ffffff, #e1e1e1);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#e1e1e1', GradientType='0');
        text-align: center;
        font-size: 18px;
        color: #000000;
        width: 100%;
        float: left;
        border-top: 1px solid #e1e1e1;
        border-bottom: 1px solid #cfcfcf;
        color: #999999;
        width: 60%;
    }
    .h-list .m-apply-40 {
        height: 35px;
        line-height: 32px;
        background-image: -moz-linear-gradient(top, #ffffff, #e1e1e1);
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ffffff), color-stop(1, #e1e1e1));
        background-image: -o-linear-gradient(top, #ffffff, #e1e1e1);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#e1e1e1', GradientType='0');
        text-align: center;
        font-size: 18px;
        color: #000000;
        width: 100%;
        float: left;
        border-top: 1px solid #e1e1e1;
        border-bottom: 1px solid #cfcfcf;
        color: #999999;
        width: 40%;
    }
    .h-list .m-apply-50 {
        height: 35px;
        line-height: 32px;
        background-image: -moz-linear-gradient(top, #ffffff, #e1e1e1);
        background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #ffffff), color-stop(1, #e1e1e1));
        background-image: -o-linear-gradient(top, #ffffff, #e1e1e1);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#e1e1e1', GradientType='0');
        text-align: center;
        font-size: 18px;
        color: #000000;
        width: 100%;
        float: left;
        border-top: 1px solid #e1e1e1;
        border-bottom: 1px solid #cfcfcf;
        color: #999999;
        width: 50%;
    }
    .h-list .m-machine > li {
        height: 170px !important;
    }

</style>
<div class="h-list">
    <?php if($model && count($model)>0):?>
        <ul>
            <?php foreach($model as $row):?>
                <li>
                    <a href="<?=Url::toRoute(['/shop/codeapi/parts','id'=>$parts_id,'wx_id'=>$wx_id,'fault_id'=>$row['id']])?>">
                        <div class="li-cover">
                            <img class="li-cover-img" src="<?=$row['fault_cover']?>"/>
                        </div>
                        <p class="li-row li-name">故障：<?=ConfigBase::getFaultStatus($row['fault_type'])?></p>
                        <p class="li-row-small">描述：<?=$row['desc']?></p>
                        <p class="li-row-small"><?=$row['name'],',',$row['phone']?></p>
                        <p class="li-row-small"><?=$row['address']?></p>
                        <p class="li-row-small">状态：<span style="color: red"><?=ConfigBase::getFixStatus($row['status'])?></span></p>
                    </a>
                </li>
            <?php endforeach;?>
            <li style="clear:both; display: none;"></li>
        </ul>
    <?php else:?>
        <p class="h-hint">没有符合绑定配件的维修任务</p>
    <?php endif;?>
</div>