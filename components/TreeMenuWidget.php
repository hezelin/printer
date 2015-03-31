<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
use Yii;

class TreeMenuWidget extends Widget{

    /*
     * echo TreeMenuWidget::widget(
        [
            'items' => [
                [
                    'label' => '微信管理',
                    'items' => [
                        ['label' => '我的公众号', 'url' => '/weixin/index'],
                        ['label' => '添加公众号', 'url' => '/weixin/add'],
                    ],
                ],
                [
                    'label' => '我的账号',
                    'items' => [
                        ['label' => '修改密码', 'url' => '#'],
                        ['label' => '查看日志', 'url' => '#'],
                    ],
                ],
                [
                    'label' => '权限管理',
                    'items' => [
                        ['label' => '角色管理','url' => 'auth/login'],
                        ['label' => '账户管理','url' => 'auth/login'],
                    ],
                ],
                [
                    'label' => '测试',
                    'url' => '#',
                ]
            ],
        ]);
     */
    public $options;
    public $route;
    public $items;

    public function init(){
        parent::init();
        if ($this->route === null && Yii::$app->controller !== null)
            $this->route = Yii::$app->controller->getRoute();

        $this->options = [ 'class'=>'col-sm-2 col-md-2','id'=>'tree-menu'];
    }

    public function run(){

        $this->registerScript();
        return $this->renderItems();
    }


    public function renderItems()
    {
        $items = [];
        $index = 0;
        foreach ($this->items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }
            $items[] = $this->renderItem($item,++$index);
        }

        $wrap = Html::tag('div',implode("\n", $items),['class'=>'panel panel-default']);

        return Html::tag('div', Html::tag('div',$wrap,['class'=>'panel-group','id'=>$this->getId()]), $this->options);
    }


    public  function renderItem($item,$index)
    {
        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }

        $items = ArrayHelper::getValue($item, 'items');

        $id = $this->getId() . '-collapse' . $index;
        $options['id'] = $id;

        Html::addCssClass($options, 'panel-collapse collapse');

        if( $in =  $this->isItemActive($item))
            Html::addCssClass($options,$in);

        // 有子类目
        if($items !== null  && is_array($items))
        {
            $header = '<i class="glyphicon glyphicon-menu-right"></i>&nbsp;' . $item['label'];
            $headerToggle = Html::a($header, '#' . $id, [
                    'class' => 'collapse-toggle',
                    'data-toggle' => 'collapse',
                    'data-parent' => '#' . $this->getId()
                ]) . "\n";

            $header = Html::tag('h4', $headerToggle, ['class' => 'panel-title']);
            $group = [];
            $group[] = Html::tag('div', $header, ['class' => 'panel-heading']);
            $content = Html::tag('div', $this->renderTree($items), ['class' => 'panel-body']) . "\n";
            $group[] = Html::tag('div', $content, $options);

            return implode("\n", $group);
        }

        // 没有子类目
        $url = ArrayHelper::getValue($item, 'url', '#');
        $header = ' <i class="glyphicon no-icon">&nbsp;</i> ' . $item['label'];
        $headerToggle = Html::a($header, $url, [
                'class' => 'collapse-toggle '.$in
            ]) . "\n";
        $header = Html::tag('h4', $headerToggle, ['class' => 'panel-title']);

        return Html::tag('div', $header, ['class' => 'panel-heading']);

    }

/*
 *  <ul class="nav nav-pills nav-stacked">
        <li role="presentation"><a href="#">Home</a></li>
        <li role="presentation"><a href="#">Profile</a></li>
        <li role="presentation"><a href="#">Messages</a></li>
    </ul>

 */
    public  function renderTree($items)
    {
        $group = [];
        foreach($items as $item)
        {
            $option = ['role'=>'presentation'];
            if( strpos(ltrim($item['url'],'/'),$this->route ) !== false ){
                $option['class'] = 'active';
            }
            $group[] = Html::tag('li',Html::a($item['label'],$item['url']),$option);
        }

        return Html::tag('ul',implode("\n",$group),['class'=>'nav nav-pills nav-stacked']);
    }


    /*
     * return false / in / head-active
     */
    public function isItemActive($item)
    {
        if(isset($item['items']) && is_array($item['items'])) {
            foreach ($item['items'] as $row)
                if (strpos(ltrim($row['url'], '/'), $this->route) !== false)
                    return 'in';
        }

        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])
            && (strpos(ltrim($item['url'][0],'/'),$this->route ) !== false) )
            return 'head-active';

        if(isset($item['url']) && !is_array($item['url'])
            && (strpos(ltrim($item['url'],'/'),$this->route ) !== false))
            return 'head-active';

        return false;
    }


    /*
     *  初始化 tree menu 高度
     */
    private  function registerScript()
    {
        $script = <<<JS_TREE
        $('#tree-menu').height( $(document).height() - 60 );
JS_TREE;
        $this->view->registerJs($script,\yii\web\View::POS_READY);
    }
}
?>