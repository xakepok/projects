create table `#__prj_statuses`
(
  id     int auto_increment
    primary key,
  code   tinyint      not null comment 'Код статуса',
  title  varchar(150) not null,
  weight int          not null comment 'Вес',
  constraint `#__prj_statuses_code_uindex`
    unique (code),
  constraint `#__prj_statuses_weight_uindex`
    unique (weight)
)
  comment 'Статусы сделок';

create index `#__prj_statuses_title_index`
  on `#__prj_statuses` (title);

insert into `#__prj_statuses` (`id`, `code`, `title`, `weight`) values
(null, 0, 'COM_PROJECTS_HEAD_CONTRACT_STATUS_0', 50),
(null, 1, 'COM_PROJECTS_HEAD_CONTRACT_STATUS_1', 40),
(null, 2, 'COM_PROJECTS_HEAD_CONTRACT_STATUS_2', 10),
(null, 3, 'COM_PROJECTS_HEAD_CONTRACT_STATUS_3', 20),
(null, 4, 'COM_PROJECTS_HEAD_CONTRACT_STATUS_4', 30),
(null, 7, 'COM_PROJECTS_HEAD_CONTRACT_STATUS_7', 100),
(null, 8, 'COM_PROJECTS_HEAD_CONTRACT_STATUS_8', 110),
(null, 9, 'COM_PROJECTS_HEAD_CONTRACT_STATUS_9', 120),
(null, 10, 'COM_PROJECTS_HEAD_CONTRACT_STATUS_10', 200);