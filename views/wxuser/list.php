<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '微信用户';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
 $(function () {
        $(\'.btn-batch-update\').click(function () {
            var $btn = $(this).button(\'loading\');
            $(this).addClass("btn-warning");
            
            $.post("batch-update",{},function(rst){
                if(rst == "success"){
                    document.location.href = "show-rst?rst=success";
                }else{
                    document.location.href = "show-rst?rst=error";
                }
       
                //setTimeout(function(){
                 //   $(".btn-batch-update").removeClass("btn-warning");
                 //   $btn.button(\'reset\');
               // }, 1000);
            });
        });
 
        options = new Array();
        options["html"] = true;
        options["content"] = "<div style=\'font-family: \"微软雅黑\";color:gray;\'><p >微信限制每天只能更新粉丝资料<span style=\'color:#d9534f\'> 2次</span>，请不要频繁更新！</p><p>批量更新的时间比较长，请耐性等待！</p></div>";
        
        $(".btn-batch-update").popover(options);
    });
');
?>

<?php
echo Html::a('批量更新', 'javascript:void(0);', ['class' => 'btn btn-info btn-batch-update','tabindex'=>'0', 'role'=>'button','data-toggle'=>'popover', 'data-trigger'=>'hover', 'data-original-title'=>'<span style="color:#d9534f;font-weight: bold"><span class="glyphicon glyphicon-info-sign"></span> 批量更新全部用户信息</span>','data-loading-text'=>'正在更新...','autocomplete'=>'off']);
?>

<?php
echo '<br>';
echo GridView::widget([
    'dataProvider'=> $dataProvider,
    'filterModel' => $searchModel,
    'id' => 'fix-list',
    'tableOptions' => ['class' => 'table table-striped'],
    'layout' => "{items}\n{pager}",
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],               // 系列
        'nickname',
        [
            'attribute'=>'headimgurl',
            'format'=>['html', ['Attr.AllowedRel' => 'group1']],
            'value'=>function($data)
            {
                return Html::a( Html::img( substr($data->headimgurl,0,-1) .'46',['width'=>40]),
                    $data->headimgurl.'?.jpg',
                    ['class'=>'fancybox','rel'=>'group1']
                );
            }
        ],
        [
            'attribute'=>'sex',
            'filter'=>['未知','男','女'],
            'value'=>function($data){
                if( isset($data->sex ))
                    switch($data->sex)
                    {
                        case 1: return '男';
                        case 2: return '女';
                        default:return '未知';
                    }
            }
        ],
        'city',
        'province',
        'country',
        [
            'attribute' => 'subscribe_time',
            'format'=>['date','php:Ymd H:i']
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'header' => '操作',
            'headerOptions'=>['style'=>'width:80px'],
            'template' => '{notify} &nbsp; {update}',
            'buttons' => [
                'notify' => function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-envelope"></i>',Url::toRoute(['notify/send','openid'=>$key['openid'],'id'=>$key['wx_id']]),['title'=>'发送通知']);

                },
                'update'=>function($url,$model,$key){
                    return Html::a('<i class="glyphicon glyphicon-refresh"></i>',$url,['title'=>'更新微信资料']);
                },
            ]
        ]
    ],
]);


// fancybox 图片预览插件

echo newerton\fancybox\FancyBox::widget([
    'target' => '.fancybox',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '100%',
        'maxHeight' => '100%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '100%',
        'height' => '100%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);
?>

