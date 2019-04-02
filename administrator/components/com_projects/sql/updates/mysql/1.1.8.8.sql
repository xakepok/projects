alter table `#__prc_items`
  add in_pass tinyint default 0 not null comment 'Участвует в отчёте по пропускам' after in_stat;

create index `#__prc_items_in_pass_index`
  on `#__prc_items` (in_pass);

