-- 1、维修列表视图

CREATE OR REPLACE view view_fault_data AS SELECT t1.id,t1.weixin_id,t1.machine_id,t1.type,t1.status,t1.desc,t1.content,t1.openid,t1.remark,t1.add_time,t2.maintain_count,t2.series_id,t3.cover,t4.name as brand_name,t5.id as user_id,t5.name as user_name FROM tbl_machine_service t1 LEFT JOIN tbl_machine t2 ON t1.machine_id=t2.id LEFT JOIN tbl_machine_model t3 ON t2.model_id=t3.id LEFT JOIN tbl_brand t4 ON t3.brand_id=t4.id LEFT JOIN tbl_rent_apply t5 ON t1.machine_id=t5.machine_id AND t5.enable='Y'WHERE t1.enable='Y'



