-- 为 tbl_weixin_template 增加字段 ：鉴定更新通知 ：updateNotify

ALTER TABLE `tbl_weixin_template` ADD `updateNotify`  CHAR (43)  COLLATE utf8_bin  NULL