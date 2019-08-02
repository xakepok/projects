alter table `#__prj_contracts`
    add payerID int default null null;

alter table `#__prj_contracts`
    add constraint `#__prj_contracts_#__prj_exp_id_fk`
        foreign key (payerID) references `#__prj_exp` (id);

