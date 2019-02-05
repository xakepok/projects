create table `#__prj_user_action_log`
(
  id     int auto_increment,
  userID int                           not null comment 'ID юзера',
  action set ('add', 'edit', 'delete') not null,
  itemID int                           not null,
  params text default NULL             null,
  constraint `#__prj_user_action_log_pk`
    primary key (id),
  constraint `#__prj_user_action_log_#__users_id_fk`
    foreign key (userID) references `#__users` (id)
      on update cascade on delete cascade
)
  comment 'История действий юзеров';

create index `#__prj_user_action_log_action_index`
  on `#__prj_user_action_log` (action);

create index `#__prj_user_action_log_itemID_index`
  on `#__prj_user_action_log` (itemID);

create index `#__prj_user_action_log_userID_index`
  on `#__prj_user_action_log` (userID);

alter table `#__prj_user_action_log`
  add section varchar(255) not null comment 'Раздел сайта' after userID;

create index `#__prj_user_action_log_section_index`
  on `#__prj_user_action_log` (section);

alter table `#__prj_user_action_log`
  add old_data text null comment 'Старые данные';

