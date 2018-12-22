CREATE VIEW `#__prj_contract_amounts` AS
SELECT `c`.`id` as `contractID`,
       sum(round(`i`.`price_rub` * `v`.`value` * ifnull(`v`.`value2`, 1) *
                 (case
                    when (`v`.`columnID` = '1') then `i`.`column_1`
                    when (`v`.`columnID` = '2') then `i`.`column_2`
                    when (`v`.`columnID` = '3') then `i`.`column_3`
                   end)
                   * ifnull(`v`.`markup`, 1)
         -
                 `i`.`price_rub` * `v`.`value` * ifnull(`v`.`value2`, 1) *
                 (case
                    when (`v`.`columnID` = '1') then `i`.`column_1`
                    when (`v`.`columnID` = '2') then `i`.`column_2`
                    when (`v`.`columnID` = '3') then `i`.`column_3`
                   end)
                   * if(`v`.`factor` IS NULL, 1, 1 - `v`.`factor`)))
         as `amount_rub`,
       sum(round(`i`.`price_usd` * `v`.`value` * ifnull(`v`.`value2`, 1) *
                 (case
                    when (`v`.`columnID` = '1') then `i`.`column_1`
                    when (`v`.`columnID` = '2') then `i`.`column_2`
                    when (`v`.`columnID` = '3') then `i`.`column_3`
                   end)
                   * ifnull(`v`.`markup`, 1)
         -
                 `i`.`price_usd` * `v`.`value` * ifnull(`v`.`value2`, 1) *
                 (case
                    when (`v`.`columnID` = '1') then `i`.`column_1`
                    when (`v`.`columnID` = '2') then `i`.`column_2`
                    when (`v`.`columnID` = '3') then `i`.`column_3`
                   end)
                   * if(`v`.`factor` IS NULL, 1, 1 - `v`.`factor`)))
         as `amount_usd`,
       sum(round(`i`.`price_eur` * `v`.`value` * ifnull(`v`.`value2`, 1) *
                 (case
                    when (`v`.`columnID` = '1') then `i`.`column_1`
                    when (`v`.`columnID` = '2') then `i`.`column_2`
                    when (`v`.`columnID` = '3') then `i`.`column_3`
                   end)
                   * ifnull(`v`.`markup`, 1)
         -
                 `i`.`price_eur` * `v`.`value` * ifnull(`v`.`value2`, 1) *
                 (case
                    when (`v`.`columnID` = '1') then `i`.`column_1`
                    when (`v`.`columnID` = '2') then `i`.`column_2`
                    when (`v`.`columnID` = '3') then `i`.`column_3`
                   end)
                   * if(`v`.`factor` IS NULL, 1, 1 - `v`.`factor`)))
         as `amount_eur`
FROM `#__prj_contract_items` as `v`
       left join `#__prc_items` as `i` on `i`.`id` = `v`.`itemID`
       left join `#__prj_contracts` as `c` on `c`.`id` = `v`.`contractID`
group by `v`.`contractID`;

CREATE VIEW `#__prj_contract_payments` AS
SELECT `s`.`contractID`, IFNULL(SUM(`p`.`amount`), 0) AS `payments`
FROM `#__prj_payments` AS `p`
       LEFT JOIN `#__prj_scores` AS `s` ON `s`.`id` = `p`.`scoreID`
GROUP BY `s`.`contractID`;