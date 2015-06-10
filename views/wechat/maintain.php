<?php
use yii\helpers\Url;
use app\components\CarouselWidget;
use app\components\FixedmenuWidget;

$this->title = '维修主页';

$this->registerCssFile('/css/home/'.$setting['style']);
//$this->registerJsFile('/js/iscroll5.js');

?>
<p style="width: 100%; height: 15%; float:left;">&nbsp;</p>
<style>
    body{background-color: #FFFFFF;}
</style>
<!--首页菜单-->
<ul id="home-menu">
    <li>
        <a href="<?= Url::toRoute(['m/initiative','id'=>$setting['wx_id']]) ?>" >
            <b class="color-1">
                <?php if($num['order']>0):?>
                <em class="icon-count"><?=$num['order']?></em>
                <?php endif;?>
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABEklEQVRYR+3VbQ3CMBAG4FUBFsAJEoaDzQGOwAFIQAo4wEG5Jm3SdfdNyPhRfnbbvc+OtReGjX9h4/zBDYgxjoC/5BeYQwh3z8u4ABA+VeElNyGuVoQZQIS7ESaAEO5CqAHKcDNCBTCGmxAiwBmuRrAAJnzGdgGyliDs7iABXHjabnA91lsO1oL0DLZFUYCmEAZIAZpnF/BWlU+4G6JdtJICCIhTe2KuOgCF31Bk1wBW/yMHYBBPABykDrQA9COSAATiBYC9BDjCDeVMP1NDRgPIiDS0Sr0R6j1YAPalYmtagFRPPIioAh3QO9A70DvQO/APHain5mrKSTOgXP9mFtRTc2qn3M8B2gDpPncHpMLa6x/sv7ghpOxsiwAAAABJRU5ErkJggg=="/>
            </b>
            <span>主动接单</span>
        </a>
    </li>
    <li><a href="<?= Url::toRoute(['m/task','id'=>$setting['wx_id']]) ?>" ><b class="color-2">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAB40lEQVRYR7WXjVECMRCFSQVaAh0oFQgdYAVCB1qBdqAdiBVoBx4d0AFYgVrB+R5ubkLIfy6ZuQlzt5t8t7tvw6mJZ/R9f41H77imYrLDzOtNKdX5/HLvq5ADIFZ4/uqwIcADQAhUNYIAXDkA8YPHi1qIKEBriCSACESHKCxK85AMIBBPmB8dmzEVRYWpkOMlnD9S3wD2zP2FZU9lsGCzBwH28GIY1ynesP+G3aVlu4P/LMXftiFALzc3MQiY+lIwgW9WOjWICcB7XoiAHI9r1QCwmVwZoTmDiG0O3y0A5qUp2MDxznIeILA5q56hD42qIiT5p2N1VjuHXXAukFlpRzwWDt6yw3RTEkLxWQOAkcweGoAnHyFsfecsWAQxSEeO31qIqJTtNzrRrkC8VKaDXZXR0DUUjKKzeQCEhbnCxdRoiW7x+4CLgLzv+p+gN6O0eT5EIYq6lxTuPebnwOsl/V8oBhAIVr7dQ0wmQjAd3sOuCkAgWLgxCXsVMgYAGxUhzHbuyoxTIdUAEoUpZhZerI+cKWQUAIFIbWYnChkNQCAo3ZA8dWoGhYwKkChPE+J2dIBEeWqIQxOADHl+tQSIyfMXoPNmABF5/m+Ob8umAB55DpvzeXMAgVhi5inKwQ+h4av6D6M6zURT1ORnAAAAAElFTkSuQmCC"/>
            </b>
            <span>维修中...</span></a></li>
    <li><a href="<?= Url::toRoute(['m/record','id'=>$setting['wx_id']]) ?>" ><b class="color-3">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAApElEQVRYR+2WwQ6AIAxD2ZerXz7h4MUEms6aGTOuTPbsaIO15GXJ/dsUwN33DrdNAA8zG/uP1wrAV6d3AIl61v+UaoTqWUkKIF+B2czQrGWXcAGQa0P2NkfrJV6ONh/fyQCiyakEoALtUg3akJX37g7oJlRQAK8rwDZQJ6fSBaHklAFElfwuQDTZWCXqTQijmI1WegQoiv8PoE42egT1JmQlU9efS3iV8R26gMsAAAAASUVORK5CYII=" />
    </b><span>历史维修</span></a></li>
    <li><a href="<?= Url::toRoute(['m/help','id'=>$setting['wx_id']]) ?>" ><b class="color-4">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABRklEQVRYR+2X7Q2CMBCG7QYyiTKBOoE6gbiBbqATqBOIExg2cATcwA1kA3zPtKSRtMcJNf6ApKHpvbRP7yPh1EA/ZVkOMb1iTM3al+8C3x0xTkopmnsfZQHcMJ9wHwjsD2iXgMh939gApRZGTchdm8KTZh+SkAe22C916WsAEFdrgttWUgvggsWVNqxdEMEA6CKA2QDg4IMICkAHAyLB6+yCCA6gIYwnKCdmdmL+BEBDUCJSTlB1xCbRgwEwCbwHwI40IQCo7kcMQAGAKAgAV7qmTE25d+6BHqD3QOceQNY2KSvXuTmyPbaN4ipoCXAHwLgVAOdSqV3sAekBnF4M0DIEfQ7UIiIOARdTqV0M0OeA1MWcXhwCbkOp/f8BcKNWrZnPI7oBfr7/BXUHFrI59bFkOH/x+VNK7XmKMZfGVajPoE9MX/ACSq80MMSAiw8AAAAASUVORK5CYII="/>
    </b><span>查看资料</span></a></li>
    <li><a href="<?= Url::toRoute(['m/index','id'=>$setting['wx_id']]) ?>" ><b class="color-5">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAABrUlEQVRYR+2WgVHDMAxF8QTABukElA1gAtiAMgEwAekEwASEDWACYIMyAc0G7QTwP2fdOaqdWAkU7ojvepD6R3rfkpK6D6ydYDms8Pqn/3cjwHgC/+YEYHQPE3WNz6mfrAoDN9/aFADgFokv1FjfbRNgheS7CmD52wC1GSCo5Syo5VXXExP3VdCcKZ29B5K1dO4yBeGh37HPRuSq8WETln1OIF5L5yYtAKWfAErmTCza7wJYI6i4a3DAfYEv6F6cT6Glia/VB+AR950ot08IKvOtAUL9OXRVKDABRGopsehoEjrjBvRH+PPsRa/Y53VjWQFIL50snX/jIz4gwSyMDoAFrg/8d8fYf+kNoNy8IdjUu4wmgZ4w9yk4Ack+gZQbBbYA2KEq1RrJ2HhL7Z7XWQAIyBlvO2pdGnnxMEdj7LJLoN5ehb+RbopIszEhHepnPR84jbGzAMTeXrnjJnk2GtMCEHvireB+P1ZLnJhJ39mEiYA1AKQcDQ6rPgeghIi/YMKVbCgAmPSdABT4N1/4E4pJksuqZ6CsMWxLOnTv7wEMdWS9f+MErAGG6keAT2llRON6+g2JAAAAAElFTkSuQmCC"/>
    </b><span>我的业绩</span></a></li>
    <li><a href="<?= Url::toRoute(['m/notice','id'=>$setting['wx_id']]) ?>" ><b class="color-6">
                <?php if($num['new']>0):?>
                <em class="icon-count"><?=$num['new']?></em>
                <?php endif;?>
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAACaElEQVRYR9VWi1HCQBA1HdiBoQKlAqECtQKxAqECsQKhAqUDqMBQgVCBKcEO4ns3F2bZ3GcDM854MxmOZG/33dtvcWFcTdOMIPqOp4wcqfH+qSiKyqjSiRVWYQCggauMfA0AA6vOvgAai2IAMF/qfwHQt4dLHCN9b6z19KJLHv5zADDI6H/Ew2zgvs0GBicfRv8KjHBvXlkGfPq9eMMWxQTyak3HJAAYX0DZs8VqQGYBELPc2SAAGL7EwU88N0rBylO9Uy6gHF1DF8lFuTGA/MSAxADw4LU4tMF+Kv0LkHN+xzv3y+XjhKzdibM7yAzNAAK0z6CASo9WCIAAMsX+TRxYQgffddYRAz7gSH27gsb9bTsMSO3QpUHQFZVGoAFQ4NYLbXDgPkYdDHzwG2QmCZm1cEcF2XEUABQykL6EwCCV05B3t4HMKAGgxLdv8X0IecbXYR0YUJSxoERv5l2QBeDlyFSbHR2XSgCSfvZ1R3HidlYAvAjnCK6tZkwCkKnXoUoDsfYC5dpOSkoAh35v6XBWAN4NUd3nAHDBBLC6Wna81oL18keZd7ILUvEhvykX7DXgk4OwBwBzEMrKlU3DHgDMaVhCqSwayUJkAeCbU1JnqhSv4a8Hi6FErZCluFMDXFCqgBnhv6kZ5YAFmlGwtnTmgUA75hywzBlUFzmtHbdKfKNpuyJfk0rW8ToFxPucc4DsokHqWz2pkYxGJQieYURXeJjPrhD5POf0RPdNFMAtwfQeyQQT5wyl0SlIgrSO5fMAGzFv8NaMm6O+HxPOAhBslN639C+n5nZo3WPPqZcuY+rWMWOh978rjTIwDHzNNAAAAABJRU5ErkJggg=="/>
    </b><span>最新通知</span></a></li>
</ul>
