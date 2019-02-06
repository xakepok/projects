alter table `#__prj_user_action_log`
  drop column dat;

alter table `#__prj_user_action_log`
  add dat timestamp default now() null after id;
