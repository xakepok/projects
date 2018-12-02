ALTER TABLE `#__prj_contracts` DROP `state`;
ALTER TABLE `#__prj_projects` DROP `state`;
ALTER TABLE `#__prj_exp`
  DROP `checked_out`,
  DROP `checked_out_time`,
  DROP `state`;
ALTER TABLE `#__prj_plans` DROP `state`;
ALTER TABLE `#__prj_payments` DROP `state`;