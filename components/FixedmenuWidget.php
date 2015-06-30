<?php
namespace app\components;

use yii\base\Widget;
use yii\helpers\Url;

class FixedmenuWidget extends Widget{

    public $wx_id;
    public $phone;
    public function init(){
        parent::init();
    }

    public function run(){
        $this->registerCss();
        $this->registerScript();
        return $this->renderHtml();
    }

    public function renderHtml()
    {
        $url1 = Url::toRoute(['i/index','id'=>$this->wx_id]);
        $url2 = Url::toRoute(['help/index','id'=>$this->wx_id]);
        $content = <<< MODEL_CONTENT
<div id="fui-wap-wormhole">
    <div id="fui-wap-wormhole-list">
        <div class="wormhole-list-inner" style="width: 220px;">
            <a id="fui-a-btn" href="javascript:void(0);" class="operation-btn">
                <img class="icon-btn fui-home" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAA1klEQVRIS+2VjQ2CMBCFuQ0cxRF0BCdQJrMbOIKO4ChucL6XXEngaLnwk2BiE0KA8n33riVIs/GQjfnNvgSqmpBYRaSNJg8nMPjVwCkqmRQAfAD0geM0qPqF6wtEn1qaqsDgTwCOBcgb9881SVEAOKH3Cjw7KWkh4dmNUYHBWTnbExlsE5M4iRMAzl6z51F4LmBU0hMAfrO2RKouzWG7Un7YCVaCZ24nKa2BzomAyh1vP4JhdWipS7kowV/Q2zSR/kbmEBreRauuwZxvoPTO5P9gqez3BV+ut2oZN9upWQAAAABJRU5ErkJggg=="/>
                <img class="icon-btn fui-close" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAA7UlEQVRIS+1UwQ3CMAxsNmAT2ICyAWwCk8AmdANgA9iEDcJZSqRixfZVVR9IVPKjjX1nXy9O3cJPWhi/+xOECjclyjlvUHlGHFJKbw8FuSucXxEn5D51rkVwR+IWIQU7i6SA35AjDT2Q17ME0pWQrC0SBf5CXt9qxHRRAWiSsOAyjWvTFkmRoMpidl6lCu9Bg0RqRfMQPJygdqFI5DMFPpWgyiJ1rrvGTmIlGmsu9aa7KJsqab5+aDlzLUxN4FnRszA1AeNzliRaFa5bFMmkVSE+vyD25LIbkHukl53Wcc57aNM54PRFm0Py+xN8AMzThBnUAItNAAAAAElFTkSuQmCC"/>
            </a>
            <a href="{$url1}">
                <img class="icon-i" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAABK0lEQVRIS+WT/Q2CMBDF6QS6AbiBI7CBbiBuoJPoBuIGuAEjuIGwgU6A7yWHwX5QYkH/8JLLhXJ9v+u1p6KJTU2sH/0JoGmaBK08wFNpaYm4V0pVvhZ7WwTxOURucMau3fGxAITRaUMABXav4GeIZVQCNEfYdNdchCEAtiGG+FsuIA3WKywvQk/QB6gBSEIBbYtyiG21Fl2wtg4FsMIrfKYJPfCdBF+yVEzIEZ4KpETMfOLM9V5y3/GH/OsF4KEs5Tky0ttZ4Ntn2+h8voxWswJEuDu5vmLZMk62ATIAEM+QfBLFGjGHF/pmKYIviPkxnKcihPkvswEq2cBkXqzXANshiSc2Bs8G4IRG+uT6KDLZxj4nwCfo+q8X9n3Ap5U7TzS2oK7320ke43RPzmhzGb9E12sAAAAASUVORK5CYII=" />
                <span>我的</span>
            </a>
            <a href="{$url2}">
                <img class="icon-i" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAABtElEQVRIS7VVgVHDMAxsJqBMQDoB7QSECYANygYwAWUCYAK6Ae0EhAnaTkDYoJ0g/PvknK3YidO7+E7X2pb/ZemtZJORRzYy/qSXoK7rewRxDStguQRU4beE7bMs23YFGSUAMMG+YPOeW+6x/wAikrZGkADgT/B8gU0TU3iE3wokH9q/RSDgb8rxhPmSKZF13moNu1B+zyB5d9c8AoDz4LeKnOA5DjLKZsCXt2NaXBL6LNx0aQJGyIK6g/ndALDAImvCwbUSa7zVp/Jn4Rd2rSEQtVgA98yMEWGf0dloj1i7FCH8KgIbwIZ/XIIV5ixsyjiBYNpB8Ip94nkEJeY3KeiSIqaNatOCIMQWBHw/HkGF+VUCwSMOryOCsMcr+MzOITCRSWp2AIi9kz/45eek6FbUwwLeddw2mKLeIgPciEIpKsQTLDKLEpJpQllaLubteCmSyEIPbSjBAeBNgwy1CspV9xhD4qSojrCyrcyjrUJuEdN2CkF3s7NRyQNi0YM3CUTPyNmuvU7aqoF7ULTOQunmp/F/sLAc9MFRRFQXi1bActmjGIxZtcSU0PtNHioh7T86wT9BfrAZHaCA3AAAAABJRU5ErkJggg=="/>
                <span>帮助</span>
            </a>
            <a href="tel:{$this->phone}">
                <img class="icon-i" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAABqklEQVRIS9WVjU3DMBCFmwmADcIElAkIE0AnoJ0ANqBsABPQDegGlAloJwAmgA3C+6Jz5FzipAUVCUun2s7dPb/7azba88r27H/0twBlWa7F6MSxmmRZtgx30vnS/lWykrzoG7/J1WAg47JD805O5hGA1wF8Jh2AW8sDdDF4kPFNBJBrj1xKppIDCc7PpYd9Y3kA6J45nbUMT7teJ8IALcwGkGPPxAOgfNV6hawiBiEHhI4HjQQUHrbU3SS29wBQfnQAGxmNI4A4B1V+jAnhIVyEqgJmeYBD3X1GABvti5i2nKFDTm5Nr3Ko+3udryWNomj1gRSpigvJh2Scqg7pzQ2EUi10JulPkurcyYBLKfLxWZKsDNPL9fuGnhweWZjqcxLAjAOLZAVFYezddo4Kl7RGH2zruJeBCxVHOnWxq3P0e4edmEylE8qWKqFCqpFg1USuKGFKdGV5o6zr2TU4TR0IjmaSdyuEuj9idgKo/Q4CROHiVWHuwCJPhWxnAAPBIWGiR3rXjwCCR+sTOjkJ9CuACIiRQfcWFi7O/Fk1ZtdWORgKSd/3/w/wDROtshmthDKLAAAAAElFTkSuQmCC" />
                <span>客服</span>
            </a>
        </div>
    </div>
</div>
MODEL_CONTENT;
        return $content;

    }

    /*
     * 部件的 脚本 js
     */
    private  function registerScript()
    {
        $jsName = 'fixedMenu'.$this->getId();
        $script = <<<MODEL_JS
        var fixedMenuHasClick = 0;
    function {$jsName} () {
        document.getElementById("fui-a-btn").onclick = function () {
            if( fixedMenuHasClick == 0){
                fixedMenuHasClick = 1;
                document.getElementById("fui-wap-wormhole-list").className = 'fui-active';
            }else{
                fixedMenuHasClick = 0;
                document.getElementById("fui-wap-wormhole-list").className = '';
            }
        };

    }
    document.addEventListener('DOMContentLoaded', {$jsName}, false);
MODEL_JS;
        $this->view->registerJs($script,\yii\web\View::POS_END);
    }

    /*
     * 部件 css
     */
    private function registerCss()
    {
        $css = <<<MODEL_CSS
    #fui-wap-wormhole {
        position: fixed;
        bottom: 20px;
        left: 20px;
    }
    #fui-wap-wormhole-list{
        overflow: hidden;
        position: relative;
        -webkit-transition: 300ms all ease;
        transition: 300ms all ease;
        background-color: #4684cf;;
        border-radius: 50px;
        height: 50px;
        z-index: 1000;
        width: 50px;
    }
    #fui-wap-wormhole-list .wormhole-list-inner {
        width: 275px;
        height: 50px;
        position: absolute;
        bottom: 0;
        left: 0;
        z-index: 2;
    }
    #fui-wap-wormhole-list a {
        width: 50px;
        height: 50px;
        color: #fff;
        font-size: 12px;
        text-align: center;
        float: left;
    }
    #fui-wap-wormhole-list .operation-btn {
        width: 50px;
        height: 50px;
        -webkit-border-radius: 50px;
        border-radius: 50px;
        overflow: hidden;
        border-top: none;
    }
    #fui-wap-wormhole-list .icon-btn{
        margin-top: 12px;
    }
    #fui-wap-wormhole-list .icon-i{
        width: 24px;
        height: 24p;
        margin: 0 auto;
        margin-top: 5px;
        position: relative;
        display: block;
    }
    .fui-active{
        width: 220px !important;
    }
    .fui-active .fui-home{ display: none;}
MODEL_CSS;
        $this->view->registerCss($css);
    }
}
?>