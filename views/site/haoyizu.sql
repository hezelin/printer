-- 1、维修列表视图
CREATE OR REPLACE view view_fault_data AS SELECT
t1.id,t1.weixin_id,t1.machine_id,t1.type,t1.status,t1.desc,t1.content,t1.openid,t1.remark,t1.add_time,
t2.maintain_count,t2.series_id,t2.cover,t2.brand_name,t2.model_name,
t3.id as user_id,t3.name as user_name
FROM tbl_machine_service t1
LEFT JOIN tbl_machine t2 ON t1.machine_id=t2.id
LEFT JOIN tbl_rent_apply t3 ON t1.machine_id=t3.machine_id AND t3.status<11
WHERE t1.status<11


-- 2、租赁方案，关联机型
CREATE OR REPLACE view view_scheme_model AS SELECT
t1.*,t2.brand_name,t2.model
FROM tbl_machine_rent_project t1
LEFT JOIN tbl_machine_model t2 ON t1.machine_model_id=t2.id


-- 3、电话维修视图(租赁资料视图)
CREATE OR REPLACE view view_rent_fault_machine AS SELECT
t1.id as rent_id,t1.machine_id,t1.wx_id,t1.openid,t1.monthly_rent,t1.black_white,t1.colours,t1.phone,t1.name,
t1.address,t1.due_time,t1.first_rent_time,t1.add_time,t1.latitude,t1.longitude,t1.status as apply_status,
t2.series_id,t2.cover,t2.brand_name,t2.model_name,t2.come_from,
t3.status,t3.id as fault_id
FROM tbl_rent_apply t1
LEFT JOIN tbl_machine t2 ON t1.machine_id=t2.id
LEFT JOIN tbl_machine_service t3 ON t1.machine_id=t3.machine_id AND t3.status < 9
WHERE t1.status<11


-- 4、收租记录
CREATE OR REPLACE view view_charge_report AS SELECT
t1.*,
t2.name as user_name,t2.address,t2.first_rent_time,t2.rent_period,
t3.model_name,t3.brand_name,t3.series_id
FROM tbl_rent_report t1
LEFT JOIN tbl_rent_apply t2 ON t1.machine_id=t2.machine_id AND t2.status<11
LEFT JOIN tbl_machine t3 ON t1.machine_id=t3.id
WHERE t1.status<11


-- 5、取消维修记录
CREATE OR REPLACE view view_fault_cancel AS SELECT
t1.*,
t2.type as fault_type,t2.content,t2.desc,t2.add_time AS apply_time,t2.remark
FROM tbl_fault_cancel_log t1
LEFT JOIN tbl_machine_service t2 ON t1.service_id=t2.id