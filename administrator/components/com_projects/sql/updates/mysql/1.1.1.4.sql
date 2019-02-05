alter table `#__prj_user_action_log`
  add dat timestamp default current_timestamp not null after id;