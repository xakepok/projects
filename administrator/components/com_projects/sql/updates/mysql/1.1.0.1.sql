create table `#__prj_templates`
(
  id int auto_increment,
  tip tinyint default 0 not null comment '0 - задача, 1 - результат',
  title text not null comment 'Название шаблона',
  text text not null comment 'Текст задания или результата',
  constraint `#__prj_templates_pk`
    primary key (id)
)
  comment 'Шаблоны заданий и результатов';

create index `#__prj_templates_tip_index`
  on `#__prj_templates` (tip);

alter table `#__prj_templates`
  add managerID int not null comment 'Владелец шаблона';

create index `#__prj_templates_managerID_index`
  on `#__prj_templates` (managerID);

alter table `#__prj_templates`
  add constraint `#__prj_templates_#__users_id_fk`
    foreign key (managerID) references `#__users` (id)
      on update cascade on delete cascade;
