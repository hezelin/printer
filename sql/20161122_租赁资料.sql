--
-- 微信租赁、租赁资料、机器租赁、微信租赁
-- view_all_data
--
CREATE OR REPLACE view view_all_data AS SELECT
t1.id as rent_id,t1.machine_id,t1.wx_id,t1.openid,t1.monthly_rent,t1.contain_paper,t1.black_white,t1.colours,
t1.due_time,t1.first_rent_time,t1.add_time,t1.latitude,t1.longitude,t1.phone,t1.name,t1.address,t1.status as apply_status,
t2.come_from,t2.series_id,t2.cover,t2.brand_name,t2.model_name,
t4.id as fault_id,t4.status,
t3.nickname,t3.headimgurl
FROM tbl_rent_apply t1
LEFT JOIN tbl_machine t2 ON t1.machine_id=t2.id
LEFT JOIN tbl_user_wechat t3 ON t1.openid=t3.openid and t1.wx_id=t3.wx_id
LEFT JOIN tbl_machine_service t4 ON t4.machine_id=t1.machine_id and t4.status<9
where t1.status<11;