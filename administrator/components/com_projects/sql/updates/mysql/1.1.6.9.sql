alter table `#__prc_items`
  add `is_electric` tinyint default 0 not null comment 'Представляет собой электричество' after `is_sq`;

create index `#__prc_items_is_electric_index`
  on `#__prc_items` (`is_electric`);

create table `#__prj_stands_advanced`
(
  id      int auto_increment,
  standID int                     not null comment 'ID стенда',
  itemID  int                     not null comment 'ID пункта прайса',
  value     decimal(3, 2) default 0 not null comment 'Кол-во',
  constraint `#__prj_stands_advanced_pk`
    primary key (id),
  constraint `#__prj_stands_advanced_#__prc_items_id_fk`
    foreign key (itemID) references `#__prc_items` (id)
      on update cascade on delete cascade,
  constraint `#__prj_stands_advanced_#__prj_stands_id_fk`
    foreign key (standID) references `#__prj_stands` (id)
      on update cascade on delete cascade
)
  comment 'Дополнительная информация о стендах';

