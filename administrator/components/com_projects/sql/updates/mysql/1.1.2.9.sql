create table `#__prj_rubrics`
(
  id int auto_increment,
  title text not null comment 'Название рубрики',
  constraint `#__prj_rubrics_pk`
    primary key (id)
)
  comment 'Рубрикатор проектов';

INSERT into `#__prj_rubrics` (title) SELECT title FROM `#__prj_activities`;

create table `#__prj_contract_rubrics`
(
  id int auto_increment,
  contractID int not null comment 'ID сделки',
  rubricID int not null comment 'ID рубрики',
  constraint `#__prj_contract_rubrics_pk`
    primary key (id),
  constraint `#__prj_contract_rubrics_#__prj_contracts_id_fk`
    foreign key (contractID) references `#__prj_contracts` (id)
      on update cascade on delete cascade,
  constraint `#__prj_contract_rubrics_#__prj_rubrics_id_fk`
    foreign key (rubricID) references `#__prj_rubrics` (id)
      on update cascade on delete cascade
)
  comment 'Привязка сделки к рубрикатору';

