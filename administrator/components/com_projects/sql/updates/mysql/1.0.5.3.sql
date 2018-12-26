ALTER TABLE `#__prc_items`
  ADD `is_sq` TINYINT NOT NULL DEFAULT 0 COMMENT 'Площадь для вывода в отчётах' AFTER `in_stat`;

ALTER VIEW `#__prj_stat` AS select `i`.`itemID`                                                  AS `itemID`,
                                      `i`.`contractID`                                              AS `contractID`,
                                      `p`.`is_sq`,
                                      `i`.`value`                                                   AS `value`,
                                      round((((((`p`.`price_rub` * `i`.`value`) * ifnull(`i`.`value2`, 1)) * (case
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
                                              if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))), 0) AS `price_rub`,
                                      round((((((`p`.`price_usd` * `i`.`value`) * ifnull(`i`.`value2`, 1)) * (case
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
                                              if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))), 0) AS `price_usd`,
                                      round((((((`p`.`price_eur` * `i`.`value`) * ifnull(`i`.`value2`, 1)) * (case
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
                                              if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))), 0) AS `price_eur`
                               from ((`#__prj_contract_items` `i` left join `#__prc_items` `p` on ((`p`.`id` = `i`.`itemID`)))
                                      left join `#__prj_contracts` `c` on ((`c`.`id` = `i`.`contractID`)));