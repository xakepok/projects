update `#__prc_items`
set `is_electric`=1
where `id` in (35, 36, 37, 38, 39, 40, 41, 42, 282, 283, 284, 285, 286, 287, 967, 968, 969, 970, 971, 972, 973, 974);
update `#__prc_items`
set `is_internet`=1
where `id` in (181, 182, 183, 184, 427, 428, 429, 430, 1112, 1113, 1114, 1115);
update `#__prc_items`
set `is_multimedia`=1
where `id` in (185, 186, 187, 188, 189, 190, 431, 431, 433, 435, 436, 1116, 1117, 1118, 1119, 1120, 1121);
update `#__prc_items`
set `is_water`=1
where `id` in (43, 290, 975);
update `#__prc_items`
set `is_cleaning`=1
where `id` in (156, 402, 1087);

alter table `#__prj_stands_advanced`
  modify `value` decimal(9, 2) default 0.00 not null comment 'Кол-во';

insert into `#__prj_stands_advanced` (`standID`, `itemID`, `value`)
  (select `s`.`id`, `v`.`itemID`, round(`v`.`value`, 2)
   from `#__prj_contract_item_values` as `v`
          right join `#__prj_contract_stands` as `s` on `s`.`contractID` = `v`.`contractID`
   where `v`.`itemID` in
         (35, 36, 37, 38, 39, 40, 41, 42, 282, 283, 284, 285, 286, 287, 967, 968, 969, 970, 971, 972, 973, 974, 181,
          182, 183, 184, 427, 428, 429, 430, 1112, 1113, 1114, 1115, 185, 186, 187, 188, 189, 190, 431, 431, 433, 435,
          436, 1116, 1117, 1118, 1119, 1120, 1121, 43, 290, 975, 156, 402, 1087)
     and `v`.`contractID` in (select `contractID`
                              from `#__prj_stands`
                              group by `contractID`
                              having count(`id`) = 1));

create table `#__tmp_army`
(
  id int auto_increment,
  title_old text not null,
  exhibitorID int default null null,
  constraint `#__tmp_army_pk`
    primary key (id),
  constraint `#__tmp_army_#__prj_exp_id_fk`
    foreign key (exhibitorID) references `#__prj_exp` (id)
      on update cascade on delete cascade
)
  comment 'Временная таблица с экспонентами армии-2018';

