create table `#__prj_exp_parents`
(
  id          int auto_increment
    primary key,
  exhibitorID int not null comment 'ID экспонента',
  parentID    int not null comment 'ID родителя',
  constraint `#__prj_exp_parents_exhibitorID_uindex`
    unique (exhibitorID),
  constraint `#__prj_exp_parents_#__prj_exp_id_fk`
    foreign key (exhibitorID) references `#__prj_exp` (id)
      on update cascade on delete cascade,
  constraint `#__prj_exp_parents_#__prj_exp_id_fk_2`
    foreign key (exhibitorID) references `#__prj_exp` (id)
      on update cascade on delete cascade
)
  comment 'Родители экспонентов';

create index `#__prj_exp_parents_parentID_index`
  on `#__prj_exp_parents` (parentID);

alter table `#__prj_contracts`
  add number_free varchar(20) default null null comment 'Произвольный номер договора' after number;

