alter table `#__prj_stands`
  add arrival date default NULL null comment 'Дата заезда';

alter table `#__prj_stands`
  add department date default NULL null comment 'Дата выезда';

create index `#__prj_stands_arrival_index`
  on `#__prj_stands` (arrival);

create index `#__prj_stands_department_index`
  on `#__prj_stands` (department);

alter table `#__prj_projects`
  add contract_prefix varchar(10) default null null;

