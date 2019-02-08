alter table `#__prj_stands`
  add `delegate` int default NULL null after `contractID`;

create index `#__prj_stands_delegate_index`
  on `#__prj_stands` (`delegate`);

alter table `#__prj_stands`
  add constraint `#__prj_stands_#__prj_contracts_id_fk`
    foreign key (`delegate`) references `#__prj_contracts` (id);

