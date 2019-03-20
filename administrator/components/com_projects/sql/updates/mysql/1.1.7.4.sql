create or replace view `#__prj_contract_item_values` as
  select `s`.`itemID`                                                AS `itemID`,
         `s`.`contractID`                                            AS `contractID`,
         if((`inf`.`tip` = 1), count(`s`.`id`), sum(`cat`.`square`)) AS `value`,
         if((`inf`.`tip` = 1),
            sum((to_days(`s`.`department`) - to_days(`s`.`arrival`))),
            NULL)                                                    AS `value2`,
         'sq'                                                        as `tip`
  from `#__prj_stands` `s`
         left join `#__prj_contracts` `c` on `c`.`id` = `s`.`contractID`
         left join `#__prj_contract_info` `inf` on `inf`.`contractID` = `s`.`contractID`
         left join `#__prj_catalog` `cat` on `cat`.`id` = `s`.`catalogID`
         left join `#__prc_items` `pi` on `pi`.`id` = `s`.`itemID`
  where ((`pi`.`is_sq` = 1) and (`c`.`id` is not null))
  group by `s`.`itemID`, `s`.`contractID`
  union
  select `ci`.`itemID`     AS `itemID`,
         `ci`.`contractID` AS `contractID`,
         `ci`.`value`      AS `value`,
         `ci`.`value2`     AS `value2`,
         'standart'        as `tip`
  from `#__prj_contract_items` `ci`
         left join `#__prj_contracts` `c` on `c`.`id` = `ci`.`contractID`
         left join `#__prc_items` `pi` on `pi`.`id` = `ci`.`itemID`
  where `pi`.`is_sq` = 0
    and `pi`.`is_electric` = 0
    and `pi`.`is_internet` = 0
    and `pi`.`is_multimedia` = 0
    and `pi`.`is_water` = 0
    and `pi`.`is_cleaning` = 0
    and `c`.`id` is not null
  union
  select `a`.`itemID`     as `itemID`,
         `s`.`contractID` as `contractID`,
         sum(`a`.`value`) as `value`,
         null             as `value2`,
         'new'            as `tip`
  from `#__prj_stands_advanced` as `a`
         left join `#__prj_stands` as `s` on `s`.`id` = `a`.`standID`
  group by `a`.`itemID`, `s`.`contractID`;

create or replace view `#__prj_todo_list` as
select `t`.`id`                                                             AS `id`,
       `t`.`dat`                                                            AS `dat`,
       `t`.`is_notify`                                                      AS `is_notify`,
       `t`.`dat_open`                                                       AS `dat_open`,
       `t`.`dat_close`                                                      AS `dat_close`,
       `t`.`task`                                                           AS `task`,
       `t`.`result`                                                         AS `result`,
       `t`.`state`                                                          AS `state`,
       `t`.`managerID`                                                      AS `managerID`,
       `u`.`name`                                                           AS `manager`,
       `u1`.`name`                                                          AS `open`,
       `c`.`id`                                                             AS `contractID`,
       `c`.`number`                                                         AS `number`,
       `c`.`status`                                                         AS `contract_status`,
       `c`.`dat`                                                            AS `contract_dat`,
       `e`.`id`                                                             AS `exhibitorID`,
       ifnull(`e`.`title_ru_short`, `e`.`title_ru_full`)                    AS `exhibitor`,
       `p`.`id`                                                             AS `projectID`,
       `p`.`title_ru`                                                       AS `project`,
       if((cast(`t`.`dat` as date) < curdate() and `t`.`state` != 1), 1, 0) AS `is_expire`
from `#__prj_todos` `t`
       left join `#__users` `u` on `u`.`id` = `t`.`managerID`
       left join `#__users` `u1` on `u1`.`id` = `t`.`userOpen`
       left join `#__prj_contracts` `c` on `c`.`id` = `t`.`contractID`
       left join `#__prj_exp` `e` on `e`.`id` = `c`.`expID`
       left join `#__prj_projects` `p` on `p`.`id` = `c`.`prjID`;