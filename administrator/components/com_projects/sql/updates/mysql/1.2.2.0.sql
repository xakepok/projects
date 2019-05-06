alter table `#__prj_contract_items`
    add managerID int default null null;

alter table `#__prj_contract_items`
    add updated timestamp default current_timestamp on update current_timestamp not null;

alter table `#__prj_contract_items`
    add constraint `#__prj_contract_items_#__users_id_fk`
        foreign key (managerID) references `#__users` (id);

