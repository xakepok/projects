create or replace view `#__prj_contract_item_values` as
  select `s`.`itemID`, `s`.`contractID`,
         if(`inf`.`tip`=1,count(`s`.`id`),sum(`cat`.`square`)) as `value`
  from `#__prj_stands` as `s`
         left join `#__prj_contracts` as `c` on `c`.`id` = `s`.`contractID`
         left join `#__prj_contract_info` as `inf` on `inf`.`contractID` = `s`.`contractID`
         left join `#__prj_catalog` as `cat` on `cat`.`id` = `s`.`catalogID`
         left join `#__prc_items` as `pi` on `pi`.`id` = `s`.`itemID`
  where `pi`.`is_sq` = 1 and `c`.`id` is not null
  group by `s`.`itemID`, `s`.`contractID`
  union
  select `ci`.`itemID`, `ci`.`contractID`, `ci`.`value`
  from `#__prj_contract_items` as `ci`
         left join `#__prj_contracts` as `c` on `c`.`id` = `ci`.`contractID`
         left join `#__prc_items` as `pi` on `pi`.`id` = `ci`.`itemID`
  where `pi`.`is_sq` = 0 and `c`.`id` is not null;

create or replace view `#__prj_stat` as
select `i`.`itemID`                                                                                           AS `itemID`,
       `i`.`contractID`                                                                                       AS `contractID`,
       `p`.`is_sq`                                                                                            AS `is_sq`,
       IFNULL(`p`.`title_ru`, `p`.`title_en`)                                                                 AS `title`,
       `c`.`currency`                                                                                         as `currency`,
       round(((case
                 when (`c`.`currency` = 'rub') then `p`.`price_rub`
                 when (`c`.`currency` = 'usd') then `p`.`price_usd`
                 when (`c`.`currency` = 'eur') then `p`.`price_eur` end) * (case
                                                                              when (`i`.`columnID` = '1')
                                                                                then `p`.`column_1`
                                                                              when (`i`.`columnID` = '2')
                                                                                then `p`.`column_2`
                                                                              when (`i`.`columnID` = '3')
                                                                                then `p`.`column_3` end)), 2) as `cost`,
       round(`v`.`value`, 2)                                                                                  AS `value`,
       round(`i`.`value2`, 2)                                                                                 AS `value2`,
       round(100 - 100 * `i`.`factor`)                                                                        AS `factor`,
       round(`i`.`markup` * 100 - 100)                                                                        AS `markup`,
       round(((((((case
                     when (`c`.`currency` = 'rub') then `p`.`price_rub`
                     when (`c`.`currency` = 'usd') then `p`.`price_usd`
                     when (`c`.`currency` = 'eur') then `p`.`price_eur` end) * `v`.`value`) *
                 ifnull(`i`.`value2`, 1)) * (case
                                               when (`i`.`columnID` = '1')
                                                 then `p`.`column_1`
                                               when (`i`.`columnID` = '2')
                                                 then `p`.`column_2`
                                               when (`i`.`columnID` = '3')
                                                 then `p`.`column_3` end)) *
               ifnull(`i`.`markup`, 1)) -
              (((((case
                     when (`c`.`currency` = 'rub') then `p`.`price_rub`
                     when (`c`.`currency` = 'usd') then `p`.`price_usd`
                     when (`c`.`currency` = 'eur') then `p`.`price_eur` end) * `v`.`value`) *
                 ifnull(`i`.`value2`, 1)) * (case
                                               when (`i`.`columnID` = '1')
                                                 then `p`.`column_1`
                                               when (`i`.`columnID` = '2')
                                                 then `p`.`column_2`
                                               when (`i`.`columnID` = '3')
                                                 then `p`.`column_3` end)) *
               if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))), 2)                                          AS `price`
from `#__prj_contract_items` as `i`
       left join `#__prj_contract_item_values` as `v`
                 on `v`.`contractID` = `i`.`contractID` and `v`.`itemID` = `i`.`itemID`
       left join `#__prc_items` `p` on `p`.`id` = `i`.`itemID`
       left join `#__prj_contracts` `c` on `c`.`id` = `i`.`contractID`
where `c`.`id` is not null;

create or replace view `#__prj_contract_amounts` as
select `contractID`, sum(`price`) as `price`
from `#__prj_stat`
group by `contractID`;

create or replace view `#__prj_stat_by_currency` as
select `i`.`itemID`                                                         AS `itemID`,
       `i`.`contractID`                                                     AS `contractID`,
       `inf`.`tip`                                                          as `tip`,
       `p`.`is_sq`                                                          AS `is_sq`,
       `i`.`value`                                                          AS `value`,
       if((`c`.`currency` = 'rub'),
          round((((((`p`.`price_rub` * if(`inf`.`tip` = 0, `i`.`value`, 1)) * ifnull(`i`.`value2`, 1)) * (case
                                                                                                            when (`i`.`columnID` = '1')
                                                                                                              then `p`.`column_1`
                                                                                                            when (`i`.`columnID` = '2')
                                                                                                              then `p`.`column_2`
                                                                                                            when (`i`.`columnID` = '3')
                                                                                                              then `p`.`column_3` end)) *
                  ifnull(`i`.`markup`, 1)) -
                 ((((`p`.`price_rub` * `i`.`value`) * ifnull(`i`.`value2`, 1)) * (case
                                                                                    when (`i`.`columnID` = '1')
                                                                                      then `p`.`column_1`
                                                                                    when (`i`.`columnID` = '2')
                                                                                      then `p`.`column_2`
                                                                                    when (`i`.`columnID` = '3')
                                                                                      then `p`.`column_3` end)) *
                  if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))), 2), 0) AS `price_rub`,
       if((`c`.`currency` = 'usd'),
          round((((((`p`.`price_usd` * if(`inf`.`tip` = 0, `i`.`value`, 1)) * ifnull(`i`.`value2`, 1)) * (case
                                                                                                            when (`i`.`columnID` = '1')
                                                                                                              then `p`.`column_1`
                                                                                                            when (`i`.`columnID` = '2')
                                                                                                              then `p`.`column_2`
                                                                                                            when (`i`.`columnID` = '3')
                                                                                                              then `p`.`column_3` end)) *
                  ifnull(`i`.`markup`, 1)) -
                 ((((`p`.`price_usd` * `i`.`value`) * ifnull(`i`.`value2`, 1)) * (case
                                                                                    when (`i`.`columnID` = '1')
                                                                                      then `p`.`column_1`
                                                                                    when (`i`.`columnID` = '2')
                                                                                      then `p`.`column_2`
                                                                                    when (`i`.`columnID` = '3')
                                                                                      then `p`.`column_3` end)) *
                  if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))), 2), 0) AS `price_usd`,
       if((`c`.`currency` = 'eur'),
          round((((((`p`.`price_eur` * if(`inf`.`tip` = 0, `i`.`value`, 1)) * ifnull(`i`.`value2`, 1)) * (case
                                                                                                            when (`i`.`columnID` = '1')
                                                                                                              then `p`.`column_1`
                                                                                                            when (`i`.`columnID` = '2')
                                                                                                              then `p`.`column_2`
                                                                                                            when (`i`.`columnID` = '3')
                                                                                                              then `p`.`column_3` end)) *
                  ifnull(`i`.`markup`, 1)) -
                 ((((`p`.`price_eur` * `i`.`value`) * ifnull(`i`.`value2`, 1)) * (case
                                                                                    when (`i`.`columnID` = '1')
                                                                                      then `p`.`column_1`
                                                                                    when (`i`.`columnID` = '2')
                                                                                      then `p`.`column_2`
                                                                                    when (`i`.`columnID` = '3')
                                                                                      then `p`.`column_3` end)) *
                  if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))), 2), 0) AS `price_eur`
from ((`#__prj_contract_items` `i` left join `#__prc_items` `p` on ((`p`.`id` = `i`.`itemID`)))
  left join `#__prj_contracts` `c` on ((`c`.`id` = `i`.`contractID`)))
       left join `#__prj_contract_info` as `inf` on `inf`.`contractID` = `i`.`contractID`
where `c`.`id` is not null;