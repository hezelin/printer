--
-- 权限功能增加操作 + 用户表修改 + 增加用户登录日志
--

--
-- 表的结构 `tbl_user`
--
CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT '用户id',
  `phone` char(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机',
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机',
  `ip` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'ip 地址',
  `access_token` char(60) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '令牌',
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT '10' COMMENT '10为有效',
  `role` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'super' COMMENT '角色',
  `group_id` int(11) NOT NULL DEFAULT '-1' COMMENT '群组id',
  `weixin_id` int(11) NOT NULL DEFAULT '-1' COMMENT '微信id',
  `created_at` int(11) DEFAULT NULL COMMENT '创建时间',
  `updated_at` int(11) DEFAULT NULL COMMENT '修改时间',
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

