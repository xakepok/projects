alter table `#__prc_items`
  add `need_period` tinyint default 0 not null comment ' Требуется указать временной период';

create index `#__prc_items_need_period_index`
  on `#__prc_items` (`need_period`);

alter table `#__prj_contract_items`
  add `arrival` date default null null after `value2`;

