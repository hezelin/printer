<?php
$this->title = '积分赠送';
$this->params['breadcrumbs'][] = $this->title;
?>
<h4>用户微信资料</h4>

<?php
    function getSex($sex){
        return $sex==1? '男':($sex==2? '女':'未知');
    }
?>
<div id="user-info" class="alert alert-info" role="alert">
    <?php if($model):?>
    <p> <img src="<?= substr($model['headimgurl'],0,-1)?>46" />&nbsp;&nbsp;&nbsp;
    <?= $model['nickname'],' , ',getSex($model['sex']),' , ',$model['country'],$model['province'],$model['city']?>
    &nbsp;&nbsp;
    <span class="small"> <?= date('Y-m-d H:i',$model['subscribe_time'])?>关注</span>
    </p>
    <p>用户积分：<?=$model['count']['score']? :0?></p>
    <p><a class="btn btn-success" href="<?=\yii\helpers\Url::toRoute(['wxuser/select','url'=>\Yii::$app->request->url])?>">重新指定用户</a></p>
    <?php else:?>
     <p>等待用户扫描</p>
     <p>或者指定用户发送积分 <a class="btn btn-success" href="<?=\yii\helpers\Url::toRoute(['wxuser/select','url'=>\Yii::$app->request->url])?>">指定用户</a></p>
    <?php endif;?>
</div>
<p class="text-danger">填入负数，则表示减去相应积分</p>
<br/>
<form class="form-horizontal" method="post">
<div class="form-group field-tblmachine-amount">
    <input name="_csrf" type="hidden" value="<?= \Yii::$app->request->csrfToken?>"/>
    <label class="col-lg-2 control-label" for="tblmachine-amount">赠送积分</label>
    <div class="col-lg-2">
        <input type="text" id="score-input" class="form-control" name="score" placeholder="整数 / 负数">
    </div>
    <div class="col-lg-3">
        <button type="submit" id="sub-btn" class="btn btn-primary">提交</button>
    </div>
</div>
</form>

<script>
<?php $this->beginBlock('JS_END') ?>
    var digit = /^-?\d+$/;
    var isScan = <?=$isScan?>;
    $('#sub-btn').click(function(){
        var score = $.trim( $('#score-input').val() );
        if( !score ){
            alert('积分不能为空');
            return false;
        }

        if(!digit.test(score)){
            alert('积分格式错误！')
            return false;
        }

    });

    function getUser()
    {
        $.getJSON('<?=\yii\helpers\Url::toRoute(['api'])?>','',function(res){
            if( res.status == 1){
                clearInterval(getApi);
                $('#user-info').html(res.data);
            }
        });
    }

//    每 5s 请求服务器获取新用户
    if(isScan == 0){
        var getApi = setInterval(function(){getUser();},5000);
    }

<?php $this->endBlock();?>
</script>
<?php
    $this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>