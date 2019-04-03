create table `#__prc_item_notifies`
(
  id int auto_increment,
  itemID int not null comment 'ID пункта прайс-листа',
  managerID int not null comment 'ID сотрудника',
  constraint `#__prc_item_notifies_pk`
    primary key (id),
  constraint `#__prc_item_notifies_#__prc_items_id_fk`
    foreign key (itemID) references `#__prc_items` (id)
      on update cascade on delete cascade,
  constraint `#__prc_item_notifies_#__users_id_fk`
    foreign key (managerID) references `#__users` (id)
      on update cascade on delete cascade
)
  comment 'Уведомления об изменении значений';

create unique index `#__prc_item_notifies_itemID_managerID_uindex`
  on `#__prc_item_notifies` (itemID, managerID);

