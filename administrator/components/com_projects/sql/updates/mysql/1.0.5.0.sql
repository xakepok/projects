CREATE VIEW `#__prj_stat` AS
SELECT `i`.`itemID`,
       `i`.`contractID`,
       `i`.`value`,
       round(`p`.`price_rub` * `i`.`value` * ifnull(`i`.`value2`, 1) *
             (case
                when (`i`.`columnID` = '1') then `p`.`column_1`
                when (`i`.`columnID` = '2') then `p`.`column_2`
                when (`i`.`columnID` = '3') then `p`.`column_3`
               end)
               * ifnull(`i`.`markup`, 1)
         -
             `p`.`price_rub` * `i`.`value` * ifnull(`i`.`value2`, 1) *
             (case
                when (`i`.`columnID` = '1') then `p`.`column_1`
                when (`i`.`columnID` = '2') then `p`.`column_2`
                when (`i`.`columnID` = '3') then `p`.`column_3`
               end)
               * if(`i`.`factor` IS NULL, 1, 1 - `i`.`factor`)) as `price_rub`,
       round(`p`.`price_usd` * `i`.`value` * ifnull(`i`.`value2`, 1) *
             (case
                when (`i`.`columnID` = '1') then `p`.`column_1`
                when (`i`.`columnID` = '2') then `p`.`column_2`
                when (`i`.`columnID` = '3') then `p`.`column_3`
               end)
               * ifnull(`i`.`markup`, 1)
         -
             `p`.`price_usd` * `i`.`value` * ifnull(`i`.`value2`, 1) *
             (case
                when (`i`.`columnID` = '1') then `p`.`column_1`
                when (`i`.`columnID` = '2') then `p`.`column_2`
                when (`i`.`columnID` = '3') then `p`.`column_3`
               end)
               * if(`i`.`factor` IS NULL, 1, 1 - `i`.`factor`)) as `price_usd`,
       round(`p`.`price_eur` * `i`.`value` * ifnull(`i`.`value2`, 1) *
             (case
                when (`i`.`columnID` = '1') then `p`.`column_1`
                when (`i`.`columnID` = '2') then `p`.`column_2`
                when (`i`.`columnID` = '3') then `p`.`column_3`
               end)
               * ifnull(`i`.`markup`, 1)
         -
             `p`.`price_eur` * `i`.`value` * ifnull(`i`.`value2`, 1) *
             (case
                when (`i`.`columnID` = '1') then `p`.`column_1`
                when (`i`.`columnID` = '2') then `p`.`column_2`
                when (`i`.`columnID` = '3') then `p`.`column_3`
               end)
               * if(`i`.`factor` IS NULL, 1, 1 - `i`.`factor`)) as `price_eur`
FROM `#__prj_contract_items` as `i`
       LEFT JOIN `#__prc_items` as `p` ON `p`.`id` = `i`.`itemID`
       LEFT JOIN `#__prj_contracts` as `c` on `c`.`id` = `i`.`contractID`