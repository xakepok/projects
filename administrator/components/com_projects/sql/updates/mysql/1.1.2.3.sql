create table `#__prj_stands_delegate`
(
  id int auto_increment,
  standID int not null comment 'ID стенда',
  contractID int not null comment 'ID сделки',
  constraint `#__prj_stands_delegate_pk`
    primary key (id),
  constraint `#__prj_stands_delegate_#__prj_contracts_id_fk`
    foreign key (contractID) references `#__prj_contracts` (id)
      on update cascade on delete cascade,
  constraint `#__prj_stands_delegate_#__prj_stands_id_fk`
    foreign key (standID) references `#__prj_stands` (id)
      on update cascade on delete cascade
)
  comment 'Делегирование стендов соэкспонентам';

create unique index `#__prj_stands_delegate_standID_contractID_uindex`
  on `#__prj_stands_delegate` (standID, contractID);

alter table `#__prj_stands`
  drop foreign key `#__prj_stands_#__prj_contracts_id_fk`;

drop index `#__prj_stands_delegate_index` on `#__prj_stands`;

alter table `#__prj_stands`
  drop column delegate;