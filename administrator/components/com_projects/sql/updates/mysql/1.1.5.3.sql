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
       ifnull(round(`v`.`value`, 2), 0)                                                                       AS `value`,
       round(`v`.`value2`, 2)                                                                                 AS `value2`,
       round(100 - 100 * `i`.`factor`)                                                                        AS `factor`,
       round(`i`.`markup` * 100 - 100)                                                                        AS `markup`,
       round(((((((case
                     when (`c`.`currency` = 'rub') then `p`.`price_rub`
                     when (`c`.`currency` = 'usd') then `p`.`price_usd`
                     when (`c`.`currency` = 'eur') then `p`.`price_eur` end) * if(`inf`.`tip` = 0, `v`.`value`, 1)) *
                 ifnull(`v`.`value2`, 1)) * (case
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
                     when (`c`.`currency` = 'eur') then `p`.`price_eur` end) * if(`inf`.`tip` = 0, `v`.`value`, 1)) *
                 ifnull(`v`.`value2`, 1)) * (case
                                               when (`i`.`columnID` = '1')
                                                 then `p`.`column_1`
                                               when (`i`.`columnID` = '2')
                                                 then `p`.`column_2`
                                               when (`i`.`columnID` = '3')
                                                 then `p`.`column_3` end)) *
               if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))), 2)                                          AS `price`
from `#__prj_contract_item_values` as `v`
       left join `#__prj_contract_items` as `i`
                 on (`v`.`contractID` = `i`.`contractID` and `v`.`itemID` = `i`.`itemID`)
       left join `#__prc_items` `p` on `p`.`id` = `i`.`itemID`
       left join `#__prj_contracts` `c` on `c`.`id` = `i`.`contractID`
       left join `#__prj_contract_info` as `inf` on `inf`.`contractID` = `i`.`contractID`
where `c`.`id` is not null;

