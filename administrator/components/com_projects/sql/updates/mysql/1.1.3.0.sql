create table `#__prj_project_rubrics`
(
  id int auto_increment,
  projectID int not null comment 'ID проекта',
  rubricID int not null comment 'ID рубрики',
  constraint `#__prj_project_rubrics_pk`
    primary key (id),
  constraint `#__prj_project_rubrics_#__prj_projects_id_fk`
    foreign key (projectID) references `#__prj_projects` (id)
      on update cascade on delete cascade,
  constraint `#__prj_project_rubrics_#__prj_rubrics_id_fk`
    foreign key (rubricID) references `#__prj_rubrics` (id)
      on update cascade on delete cascade
)
  comment 'Привязка рубрик к проекту';

