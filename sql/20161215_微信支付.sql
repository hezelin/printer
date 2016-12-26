--
-- 表的结构 `tbl_pay_log`
--
CREATE TABLE IF NOT EXISTS `tbl_pay_log` (
  `id` bigint(19) unsigned NOT NULL COMMENT '订单id',
  `openid` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '微信id',
  `money` int(11) NOT NULL COMMENT '消费金额（分）',
  `trade_no` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT '订单id',
  `body` varchar(200) COLLATE utf8_bin DEFAULT NULL COMMENT '支付内容',
  `pay_from` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '支付方式',
  `created_at` int(10) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='充值记录表';

--
-- 付款记录 `view_pay_weixin`
--
CREATE OR REPLACE view view_pay_weixin AS SELECT
t1.*,
t2.nickname,t2.headimgurl
FROM tbl_pay_log t1
LEFT JOIN tbl_user_wechat t2 ON t1.openid=t2.openid;


--
-- 付款记录 `view_pay_weixin`
--

CREATE OR REPLACE view `view_scheme_model` AS select
t1.*,
t2.brand_name,t2.brand,t2.model
from tbl_machine_rent_project t1
left join tbl_machine_model t2 on t1.machine_model_id = t2.id;

