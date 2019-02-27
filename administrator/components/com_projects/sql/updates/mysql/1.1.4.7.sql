create or replace view `#__prj_stat_by_currency` as
select `i`.`itemID`                                                         AS `itemID`,
       `i`.`contractID`                                                     AS `contractID`,
       `inf`.`tip`                                                          as `tip`,
       `p`.`is_sq`                                                          AS `is_sq`,
       `i`.`value`                                                          AS `value`,
       if((`c`.`currency` = 'rub'),
          round((((((`p`.`price_rub` * if(`inf`.`tip` = 0, `v`.`value`, 1)) * ifnull(`i`.`value2`, 1)) * (case
                                                                                                            when (`i`.`columnID` = '1')
                                                                                                              then `p`.`column_1`
                                                                                                            when (`i`.`columnID` = '2')
                                                                                                              then `p`.`column_2`
                                                                                                            when (`i`.`columnID` = '3')
                                                                                                              then `p`.`column_3` end)) *
                  ifnull(`i`.`markup`, 1)) -
                 ((((`p`.`price_rub` * `v`.`value`) * ifnull(`i`.`value2`, 1)) * (case
                                                                                    when (`i`.`columnID` = '1')
                                                                                      then `p`.`column_1`
                                                                                    when (`i`.`columnID` = '2')
                                                                                      then `p`.`column_2`
                                                                                    when (`i`.`columnID` = '3')
                                                                                      then `p`.`column_3` end)) *
                  if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))), 2), 0) AS `price_rub`,
       if((`c`.`currency` = 'usd'),
          round((((((`p`.`price_usd` * if(`inf`.`tip` = 0, `v`.`value`, 1)) * ifnull(`i`.`value2`, 1)) * (case
                                                                                                            when (`i`.`columnID` = '1')
                                                                                                              then `p`.`column_1`
                                                                                                            when (`i`.`columnID` = '2')
                                                                                                              then `p`.`column_2`
                                                                                                            when (`i`.`columnID` = '3')
                                                                                                              then `p`.`column_3` end)) *
                  ifnull(`i`.`markup`, 1)) -
                 ((((`p`.`price_usd` * `v`.`value`) * ifnull(`i`.`value2`, 1)) * (case
                                                                                    when (`i`.`columnID` = '1')
                                                                                      then `p`.`column_1`
                                                                                    when (`i`.`columnID` = '2')
                                                                                      then `p`.`column_2`
                                                                                    when (`i`.`columnID` = '3')
                                                                                      then `p`.`column_3` end)) *
                  if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))), 2), 0) AS `price_usd`,
       if((`c`.`currency` = 'eur'),
          round((((((`p`.`price_eur` * if(`inf`.`tip` = 0, `v`.`value`, 1)) * ifnull(`i`.`value2`, 1)) * (case
                                                                                                            when (`i`.`columnID` = '1')
                                                                                                              then `p`.`column_1`
                                                                                                            when (`i`.`columnID` = '2')
                                                                                                              then `p`.`column_2`
                                                                                                            when (`i`.`columnID` = '3')
                                                                                                              then `p`.`column_3` end)) *
                  ifnull(`i`.`markup`, 1)) -
                 ((((`p`.`price_eur` * `v`.`value`) * ifnull(`i`.`value2`, 1)) * (case
                                                                                    when (`i`.`columnID` = '1')
                                                                                      then `p`.`column_1`
                                                                                    when (`i`.`columnID` = '2')
                                                                                      then `p`.`column_2`
                                                                                    when (`i`.`columnID` = '3')
                                                                                      then `p`.`column_3` end)) *
                  if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))), 2), 0) AS `price_eur`
from `#__prj_contract_items` `i` left join `#__prc_items` `p` on `p`.`id` = `i`.`itemID`
            left join `#__prj_contract_item_values` as `v`
                                           on `v`.`contractID` = `i`.`contractID` and `v`.`itemID` = `i`.`itemID`
  left join `#__prj_contracts` `c` on `c`.`id` = `i`.`contractID`
       left join `#__prj_contract_info` as `inf` on `inf`.`contractID` = `i`.`contractID`
where `c`.`id` is not null;