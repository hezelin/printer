<?php
use yii\helpers\Url;
    $this->title = '工作任务'
?>
<style>
    .mod_navbar {
        border-bottom: 1px solid #e1e1e1;
        -webkit-box-shadow: 1px 0 3px #eee,inset 0 0 3px #fff;
        box-shadow: 1px 0 3px #eee,inset 0 0 3px #fff;
        padding: 0 15px;
        position: absolute;
        top: 0px;
        left:0;
        width: 100%;
        background-color: #f9f9f7;
        z-index: 3;
        height: 60px;

    }
    .row-box{
        box-shadow: 0 0 3px #eee,inset 0 0 3px #fff;
        border: solid 1px #e1e1e1;
        margin: 0 0 15px 15px;
        padding: 15px;
        background-color: #fbfbfb;
        height: 280px;
        position: relative;
    }
    .row-box .box-panel-header {
        border-bottom: 1px solid #e1e1e1;
        margin-bottom: 15px;
        display: block;
    }
    .row-box .box-panel-header h4 {
        color: #646b75;
        font-size: 18px;
        margin-bottom: 15px;
        margin-top: 0;
    }
    h4{ margin: 0}
    .empty-panel {
        margin: 0 auto;
        text-align: center;
        text-shadow: 0 1px 0 #fff,0 -1px 0 #f2f2ea;
        color: #cccccc;
        font-size: 18px;
        height: 200px;
        line-height: 200px;
    }
    .icon{ margin-right: 5px;}
    ul,li{
        list-style: none; margin: 0; padding: 0;
    }
    .box-list-li{
        height: 30px; line-height: 30px;
    }
    .m-name{  color: #333333; width: 33%; display: inline-block;}
    .m-tips{ color: #999999; width: 33%; display: inline-block;}
    
    .num-alert{color: #f7af49; font-size: 16px;}

    .fault-list-ul{
        height: 180px;
        overflow: hidden;
    }
    .fault-list-li{
        height: 60px;
        padding-top:5px;
        border-bottom: 1px dotted #cccccc;
    }
    .fault-data{ width: 40%; float: left; position: relative;}
    .fault-btn{ width: 20%; float: right; text-align: right; margin-top: 8px;}
    .fault-data span { color:#AAAAAA; height: 20px; line-height: 20px; overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap; width: 100%;}
    .fault-data h4{ color: #333333; height: 30px; line-height: 30px;overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;}
    .cover-img{
        width: 50px;
        height: 50px;
        margin-right: 10px;
        float: left;
    }
    .fault-time{
        color: #AAAAAA; font-size: 12px; font-weight: 500;
    }
    a{ cursor: pointer;}
</style>
<div class="row">
    <div class="mod_navbar">
        <div class="title"> <h3><i class="icon glyphicon glyphicon-tasks"></i>工作任务</h3> </div>
    </div>
</div>
<div class="row">
    <p style="height: 40px;">&nbsp;</p>
    <div class="col-md-8 row-box">
        <div class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-wrench"></i>待分配维修</h4>
        </div>
        <div class="box-panel-body">
            <?php if($data['fault']):?>
                <ul class="fault-list-ul">
                    <?php foreach($data['fault'] as $d):?>
                        <li class="fault-list-li">
                            <div class="fault-data">
                                <img class="cover-img" src="<?=$d['cover']?>" />
                                <h4><?=\app\models\ConfigBase::getFaultStatus($d['type'])?>,
                                    <b class="fault-time"><?=date('m月d H:i',$d['add_time'])?></b>
                                </h4>
                                <span><?=$d['desc']?></span>
                            </div>
                            <div class="fault-data">
                                <h4><?=$d['brand'],$d['model'],' , ',$d['name']?></h4>
                                <span><?=$d['phone'],',',$d['address']?></span>
                            </div>
                            <div class="fault-btn">
                                <button type="button" class="btn btn-info btn-sm">分配</button>
                                <button type="button" class="btn btn-danger btn-sm">删除</button>
                            </div>
                        </li>
                        <li class="fault-list-li">
                            <div class="fault-data">
                                <img class="cover-img" src="<?=$d['cover']?>" />
                                <h4><?=\app\models\ConfigBase::getFaultStatus($d['type'])?></h4>
                                <span><?=$d['desc']?></span>
                            </div>
                            <div class="fault-data">
                                <h4><?=$d['brand'],$d['model'],' , ',$d['name']?></h4>
                                <span><?=$d['phone'],',',$d['address']?></span>
                            </div>
                            <div class="fault-btn">
                                <button type="button" class="btn btn-info btn-sm">分配</button>
                                <button type="button" class="btn btn-danger btn-sm">删除</button>
                            </div>
                        </li>
                    <?php endforeach;?>
                </ul>
            <?php else:?>
                <div class="empty-panel">今天没有工作任务</div>
            <?php endif;?>
        </div>
    </div>
    <div class="col-md-3 row-box">
        <div class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-user"></i>维修员状态</h4>
        </div>
        <div class="box-panel-body">
            <?php if($data['maintainer']):?>
            <ul class="box-list-ul">
                <?php foreach($data['maintainer'] as $d):?>
                <li class="box-list-li">
                    <span class="m-name"><?=$d['name']?></span><span class="m-tips">待修机器</span><span class="num-alert"><?=$d['wait_repair_count']?></span>
                </li>
                <?php endforeach;?>
            </ul>
            <?php else:?>
            <div class="empty-panel">今天没有工作任务</div>
            <?php endif;?>
        </div>
    </div>

    <div class="col-md-5 row-box">
        <div class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-shopping-cart"></i>订单处理</h4>
        </div>
        <div class="box-panel-body">
            <div class="empty-panel">今天没有工作任务</div>
        </div>
    </div>
    <div class="col-md-6 row-box">
        <div class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-object-align-vertical"></i>配件处理</h4>
        </div>
        <div class="box-panel-body">
            <div class="empty-panel">今天没有工作任务</div>
        </div>
    </div>

    <div class="col-md-3 row-box">
        <div class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-bell"></i>警示模块</h4>
        </div>
        <div class="box-panel-body">
            <div class="empty-panel">今天没有工作任务</div>
        </div>
    </div>
    <div class="col-md-2 row-box">
        <a href="<?=Url::toRoute(['adminrent/list'])?>" class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-phone-alt"></i>电话报修</h4>
        </a>
        <a href="<?=Url::toRoute(['adminscore/send'])?>" class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-gift"></i>赠送积分</h4>
        </a>
    </div>
    <div class="col-md-6 row-box">
        <div class="box-panel-header">
            <h4><i class="icon glyphicon glyphicon-resize-horizontal"></i>租借申请</h4>
        </div>
        <div class="box-panel-body">
            <div class="empty-panel">今天没有工作任务</div>
        </div>
    </div>
</div>
