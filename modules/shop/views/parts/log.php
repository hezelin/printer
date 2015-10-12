<?php
use yii\helpers\Url;
$this->title = '配件记录';
?>

<style>
    <?php $this->beginBlock('CSS') ?>
    .item-list{  display: block;  padding: 12px 0;  width: 90%;  margin: 0 5%;  border-bottom: 1px #e3e3e3 solid;  font-size: 16px;  }
    .item-list h5{  height: 55px;  color: #666;  font-weight: normal;  font-size: 16px;  }
    .mtm_p{  text-align: left;  color: #999;  font-size: 14px;  }
    .item-more{  width: 80%; background-color: #efefef;font-size: 14px;  border: 1px solid #EEEEEE;  text-align: center; height: 36px; line-height: 36px;  margin: 15px auto; border-radius: 4px; color: #666666;  }
    .item-more-end{  background-color: #FFFFFF;color: #cccccc;  border: 0;  }
    .log-link{ color: #0b93d5;}
    <?php $this->endBlock() ?>
</style>
<?php
$this->registerCss($this->blocks['CSS']);


function getHtml($con)
{
    $con = json_decode($con,true);
    $html[] = $con['text'];
    if(isset($con['machine_id']))
        $html[] = \yii\helpers\Html::a('查看机器',Url::toRoute(['/rent/user-machine','id'=>$con['machine_id']]),['class'=>'log-link']);

    if(isset($con['fault_id']))
        $html[] = \yii\helpers\Html::a('查看维修',Url::toRoute(['/m/taskdetail','id'=>$con['fault_id']]),['class'=>'log-link']);

    return implode(' ，',$html);
}
?>

<div id="item-list-wrap">
    <?php if($model):?>
        <?php foreach($model as $row):?>
            <div class="item-list">
               <h5><?=getHtml($row['content'])?></h5>
               <p class="mtm_p"><?=date('Y年m月d日',$row['add_time'])?></p>
            </div>
        <?php endforeach;?>
    <?php else:?>
        <div class="h-hint">没有记录</div>
    <?php endif;?>
</div>


<?php if(count($model)<10):?>
    <div id="item-more" class="item-more item-more-end">
        没有数据了
    </div>
<?php else:?>
    <div id="item-more" class="item-more">
        查看更多
    </div>
<?php endif;?>

<script>
    <?php $this->beginBlock('JS_END') ?>
    var startId = <?=$startId?>;

    function getLocalTime(unix) {
        var str = (new Date(parseInt(unix) * 1000)).toLocaleString().replace('GMT+8', '');
        return str.substr(0,str.length-3);
    }

    function getHtml(d){
        var html = [];

        html.push('<div class="item-list">');
        html.push('<h5>'+d.content+'</h5>');
        html.push('<p class="mtm_p">时间：'+getLocalTime(d.add_time)+'</p></div>');
        return html.join('');
    }
    function getData(startId)
    {
        $.ajax({
            type:'get',
            url: '<?=Yii::$app->request->url?>',
            data:{'startId':startId,'format':'json'},
            dataType:'json',
            success:function(resp){
                if(resp.status==1){
                    var d = resp.data;
                    startId = resp.startId;

                    var html = [];
                    for(var i in d){
                        html.push( getHtml(d[i]) );
                    }
                    $('#item-list-wrap').append( html.join('') );

                    if( resp.len < 10 )
                        $('#item-more').addClass('item-more-end').text('没有数据了');
                    else
                        $('#item-more').removeClass('item-more-end').text('查看更多');
                }else{
                    $('#item-more').addClass('item-more-end').text('没有数据了');
                }
            }
        });
    }

    $(function(){
        // 点击加载更多
        $('#item-more').click(function(){
            if( $(this).hasClass('item-more-end') ) return false;
            getData(startId);
        });
    });
    <?php $this->endBlock();?>
</script>

<?php
\app\assets\ZeptoAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);

?>