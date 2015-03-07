<?php

/*
 * 配置的公共类
 */
class ConfigBase
{

    public static $applyStatus = array(
        1 => '等待提交作品',
        2 => '初赛进行中',
    );


    /*
     * 报名状态码 转换为文字
     */
    public static function applyStatus($id)
    {
        return isset(self::$applyStatus[$id]) ? self::$applyStatus[$id] : '未知状态';
    }

    public static $projectStatus = array(
        1 => '正在报名中',
        2 => '初赛公示中',
        3 => '复赛公示中',
        4 => '决赛公示中',
    );

    /*
     * 项目状态码 转换为文字
     */
    public static function projectStatus($id)
    {
        return isset(self::$projectStatus[$id]) ? self::$projectStatus[$id] : '未知状态';
    }

    public static $node = array(
        1 => '曾氏宗祠',
        2 => '渔桥节点入口',
        3 => '曾厝垵工作坊L型广场',
        4 => '中山街入口',
        5 => '加油站旁环岛路段',
    );

    /*
     * 节点名称
     */
    public static function nodeName($id)
    {
        return isset(self::$node[$id]) ? self::$node[$id] : '未知节点';
    }
}