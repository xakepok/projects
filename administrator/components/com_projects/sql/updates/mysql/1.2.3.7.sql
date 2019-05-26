create table `#__prj_ctr_items_check`
(
    id int auto_increment,
    ctrItemId int not null comment 'ID записи в основной таблице',
    columnID int default 1 not null comment 'Номер колонки',
    value double(9,2) not null,
    factor decimal(3,2) default 1 not null,
    markup decimal(3,2) default null null,
    managerID int not null comment 'ID менеджера',
    constraint `#__prj_ctr_items_check_pk`
        primary key (id),
    constraint `#__prj_ctr_items_check_#__prj_contract_items_id_fk`
        foreign key (ctrItemId) references `#__prj_contract_items` (id)
            on update cascade on delete cascade,
    constraint `#__prj_ctr_items_check_#__users_id_fk`
        foreign key (managerID) references `#__users` (id)
)
    comment 'Таблица непроверенных изменений';

alter table `#__prj_ctr_items_check` modify ctrItemId int default null null comment 'ID записи в основной таблице';

alter table `#__prj_ctr_items_check`
    add new_column int null;

alter table `#__prj_ctr_items_check`
    add is_new boolean default 0 not null;

