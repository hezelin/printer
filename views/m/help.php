<?php
use yii\helpers\Url;

$this->title = '查看资料';
?>

<div class="aui-content">
    <ul class="aui-list-view aui-in">
        <li class="aui-list-view-cell">
            <a href="<?= Url::toRoute(['rent/list','id'=>$id]) ?>" class="aui-arrow-right aui-text-default">
                <img class="aui-pull-left" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4RpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpkZTk1ZmM4NC1hYjgxLWU4NDgtOTNkYy01NmI1MzA5NDA5MDMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6N0RDNzA1NDQwMTYwMTFFNkJENkVCRDBCQkIyMDBGQUQiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6N0RDNzA1NDMwMTYwMTFFNkJENkVCRDBCQkIyMDBGQUQiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ZGM1MTg4NjItMGI3My1lMTRiLTlmN2YtMzdjMGU3NTcyY2M5IiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6M2QwYjQwYmEtZmZjNS0xMWU1LTgyMDUtOTk2MWE2YjhlYTlmIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+5w075gAAASZJREFUeNpi/P//PwOtAQuIMNn2LAFINQExHx61l4A4HojvA7EiEC8EYj086j8Bcd0ZL6kFLFCBDiAWJ+AgWyDOA+JCKG1LQD0/1NwFTFABbiJ9Lo5GEwJgc2GW/CVS019y1DMx0AGMWkKWJcwkqifWcczIit8RqekhGk0IvIPneCDIBeIKaLrGVc7cBOKJUDaIVgFiNRxqGYH4KxC3I1uyCYh3ALEoEP/DofE5EvslEIcBsSSeYH0NxL+QLfEF4koifJIHtQCU4ycT6ZPNMEumALEcgfDVB+K7QFwFxPlAHEpEnIDM3QyLeCEiI1IejSYEhMgpu/6h0aNl1wgpu5jJUQ+z5CuRml6i0YTAV+QcX0Fka2USlA2ijYlprYCzPz3aXQABBgC8FT88FYg0YgAAAABJRU5ErkJggg==">&nbsp;租机方案
            </a>
        </li>
    </ul>
    <ul class="aui-list-view aui-in">
        <li class="aui-list-view-cell">
            <a href="<?= Url::toRoute(['/shop/parts/list','id'=>$id]) ?>" class="aui-arrow-right aui-text-default">
                <img class="aui-pull-left" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4RpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpkZTk1ZmM4NC1hYjgxLWU4NDgtOTNkYy01NmI1MzA5NDA5MDMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6NEFFQzgzOEQwMTYwMTFFNkJDQTJFNTM4ODQ4MDZFRjUiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6NEFFQzgzOEMwMTYwMTFFNkJDQTJFNTM4ODQ4MDZFRjUiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ZGM1MTg4NjItMGI3My1lMTRiLTlmN2YtMzdjMGU3NTcyY2M5IiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6M2QwYjQwYmEtZmZjNS0xMWU1LTgyMDUtOTk2MWE2YjhlYTlmIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+TzZ3xwAAAjNJREFUeNq8lk1IlEEYx/fN3qKgZIs6LEGnXSRECiu6dVL7OlSsFy30YqCwhqhEdOosgSwrIaJ9bXXcSyxBoaAnRbvsIS8eTSElbXEJWXb7P/h/ZRxm3n3fQz3wc9mZ2fnPzPOlU61WI//aDsufy/kfprkzoB8kgZykBawq8+fBZ1ABr0AG/NE3WbwV2xOx2DDxbBT0gg0QA2nQwLkRCmSsN7HYrvY9yU0LcnkQ1+a3fJ/LYmXDWCMx2Ypto0OW8eOgLaR/k7VuIk58BpbBb/AQXDOsr/AZjwJHm0sBF+RBgjd+LsHiiTwBPT6nXAOTYAYUQT2jrRuc5RqXQinld3LgIU/kro/AN/AAfNfGv4IsabL89r6IeD5ZtyyS8U6DgGcFHuCnZb6oOv4OeGNYNEU/+ZkIvTOMy343VBFJ+UdgTnPydMDI+sKqoD5xH315IIQlapa0PCkGFCnyUPvVBJRMeSIRc1357nIsiJ3U9moGx3QRiev34JKy0PHeNIDd1vJGRMbBOVXkExfq1gUu1hC4CjoM45LQOVXEtWxw2nBD1a6AtyBqmT+llpWP4Kll4QVGT5b9YwecADeZQ1GfW35QRV6AOvCL/aIdtGo3ekzKluot0fmaaZDgfiOqyCbrl2dy6lk+R9D2kNaaXM1SL11uPmSpz4XtJ66l6InwS7BgmIuH7YwicsTgRCkV20zSCfrOs1hYkRIjR3rDPfaFAQpE+DnIxlSlD8dsIs7/+L/rrwADAGBDdNFQOyrqAAAAAElFTkSuQmCC" />&nbsp;配件查看
            </a>
        </li>
    </ul>

    <ul class="aui-list-view aui-in">
        <li class="aui-list-view-cell">
            <a href="<?= Url::toRoute(['help/service','id'=>$id]) ?>" class="aui-arrow-right aui-text-default">
                <img class="aui-pull-left" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABkAAAAZCAYAAADE6YVjAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA4RpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuNi1jMDY3IDc5LjE1Nzc0NywgMjAxNS8wMy8zMC0yMzo0MDo0MiAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpkZTk1ZmM4NC1hYjgxLWU4NDgtOTNkYy01NmI1MzA5NDA5MDMiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RUZGMUEyRUEwMTYwMTFFNkJGNzFDMjQ0MzkyNjkzMTAiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RUZGMUEyRTkwMTYwMTFFNkJGNzFDMjQ0MzkyNjkzMTAiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENDIDIwMTUgKFdpbmRvd3MpIj4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6ZGM1MTg4NjItMGI3My1lMTRiLTlmN2YtMzdjMGU3NTcyY2M5IiBzdFJlZjpkb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6M2QwYjQwYmEtZmZjNS0xMWU1LTgyMDUtOTk2MWE2YjhlYTlmIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+XFE7HwAAAiFJREFUeNq8lktI1FEUhx0tDBMSkaJgkqKF6ICvoEW0DB+zUkhrFSYiCgnuXA3lqrWIUDoaLqR8oEFqrcKCVqUQgova1IALEdxojCiO38GD3Bnv/c91oQe++cPcc87v3nvuK5RKpXLO2i7Iz92FdVd7PjRAPdRCGC5BEhLwEz7BIuzaEvxovHEk4rAW6INqS9sVuCb9g05YgVcwaUuUa/nvMsThvUPAZtXqH9f4k9NlWCG8g6gj2X/Yg4tQYGl/piN8DNuukQw4BH7BUyiD61AOHfDb4hvVPNbpegRtliAp7kMYhw2tx18YgUawrZo2zZcmIismZnHeh15N3gxr2vuvcAv+wGvH1MY077FIHUQsjt/hm85zXBNL3R6ouNgSHFhiI5r3WMRVaJmaJ9ANRRltpfqVArt2dNRcXTUOp0qYcLR91O89yHP41JgjCZ/ypBiEYZ3G3gC/cGbhfe0lPIebMAN3AnzTCp/0FJiFF1AFX+B+Fv+kKZLwEJDivtGYt3DbIyZhiix7BMieWdUVV+E58mVzdc3rMRFkeTpVsidCniLzpshn7WUkIEBG3X6KBbKqedMK358l6B806Zm06SHSn1l4sSkYCwjagjmYNo9xh41pPut90gNXHceMbKwh7VhJljr0BN2M0sNWGLUEF0OXXreFDoFRjd/Odv3uaIFb9e72sRX1b9f4wOvXNHkUfPB4rcgKWnC9VsRC5/HuOhRgAJ2Abz9T6R5WAAAAAElFTkSuQmCC">&nbsp;维修帮助
            </a>
        </li>
    </ul>

</div>