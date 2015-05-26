<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use app\models\ConfigBase;

$this->title = '维修进度';
?>
<?php
function getMachine($model){
    return Html::a(Html::img($model->machine->cover,['width'=>40]),$model->machine->cover,['class'=>'fancybox','rel'=>'group']) .
            ' , ' . $model->machine->brand .
            ' , ' . $model->machine->type .
            ' , ' . $model->machine->serial_id
        ;
}

function getProcess($model,$process)
{
    $html = '<ul class="list-group">';
    foreach ($process as $k => $p) {
        $row = '';
        $row .= '<i class="h-icon-circle"></i><p class="h-text">';
        $content = json_decode($p['content'], true);
        $row .= \app\models\ConfigBase::getFixStatus($content['status']);
        $row .= '<span class="small">&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y-m-d H:i', $p['add_time']) . '</span></p>';
        $html .= Html::tag('li', $row);
    }

    $html .= Html::tag('li',
            '<i class="h-icon-circle"></i><p class="h-text"><p class="h-text"> 发起维修申请
            <span class="small">&nbsp;&nbsp;&nbsp;&nbsp;'.date('Y-m-d H:i',$model->add_time).'</span></p>'
        );
    return $html.'</ul>';
}

function getImage($covers){
    $html = [];
    foreach(json_decode($covers,true) as $cover)
        $html[] = Html::a(Html::img($cover,['width'=>40]),$cover,['class'=>'fancybox','rel'=>'group']);
    return join("\n",$html);
}

?>

<?= DetailView::widget([
    'model' => $model,
    'options' => ['class' => 'table table-striped detail-view'],
    'attributes' => [
        [
            'format'=>['html', ['Attr.AllowedRel' => 'group']],
            'label'=>'故障图片',
            'value'=> getImage($model->cover)
        ],

        [
            'attribute'=>'type',
            'value'=> ConfigBase::getFaultStatus($model->type)
        ],
        'desc',
        [
            'label'=>'机器信息',
            'format'=>['html', ['Attr.AllowedRel' => 'group']],
            'value'=> getMachine($model),
        ],
        [
            'label'=>'维修进度',
            'format'=>'html',
            'value'=> getProcess($model,$process),
        ]
        /*[
            'attribute'=>'add_time',
            'value' => date("Y-m-d H:i:s",$model->add_time),
        ],
        [
            'attribute' => 'status',
            'format' => 'html',
            'value' => ConfigBase::getFixStatus($model->status),
        ],*/
    ],
]);

// fancybox 预览图片

echo newerton\fancybox\FancyBox::widget([
    'target' => '.fancybox',
    'mouse' => true,
    'config' => [
        'maxWidth' => '100%',
        'maxHeight' => '100%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => true,
        'openOpacity' => true,
    ]
]);


?>