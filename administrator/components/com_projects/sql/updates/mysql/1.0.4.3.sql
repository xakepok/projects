CREATE VIEW `#__prj_contract_amounts` AS
SELECT `v`.`contractID`,
       `c`.`expID`,
       SUM(ROUND(`i`.`price_rub` * `v`.`value` * (
         CASE
           WHEN `v`.`columnID` = '1'
             THEN `i`.`column_1`
           WHEN `v`.`columnID` = '2'
             THEN `i`.`column_2`
           WHEN `v`.`columnID` = '3'
             THEN `i`.`column_3`
           END) * IFNULL(`v`.`markup`, 1) * IFNULL(`v`.`factor`, 1) * IFNULL(`v`.`value2`, 1), 0)) AS `amount_rub`,
       SUM(ROUND(`i`.`price_usd` * `v`.`value` * (
         CASE
           WHEN `v`.`columnID` = '1'
             THEN `i`.`column_1`
           WHEN `v`.`columnID` = '2'
             THEN `i`.`column_2`
           WHEN `v`.`columnID` = '3'
             THEN `i`.`column_3`
           END) * IFNULL(`v`.`markup`, 1) * IFNULL(`v`.`factor`, 1) * IFNULL(`v`.`value2`, 1), 0)) AS `amount_usd`,
       SUM(ROUND(`i`.`price_eur` * `v`.`value` * (
         CASE
           WHEN `v`.`columnID` = '1'
             THEN `i`.`column_1`
           WHEN `v`.`columnID` = '2'
             THEN `i`.`column_2`
           WHEN `v`.`columnID` = '3'
             THEN `i`.`column_3`
           END) * IFNULL(`v`.`markup`, 1) * IFNULL(`v`.`factor`, 1) * IFNULL(`v`.`value2`, 1), 0)) AS `amount_eur`
FROM `#__prj_contract_items` AS `v`
       LEFT JOIN `#__prc_items` as `i` ON `i`.`id` = `v`.`itemID`
       LEFT JOIN `#__prj_contracts` as `c` ON `c`.`id` = `v`.`contractID`
GROUP BY `v`.`contractID`;

CREATE VIEW `#__prj_contract_payments` AS
SELECT  `s`.`contractID` , IFNULL( SUM(  `p`.`amount` ) , 0 ) AS  `payments`
FROM  `#__prj_payments` AS  `p`
        LEFT JOIN  `#__prj_scores` AS  `s` ON  `s`.`id` =  `p`.`scoreID`
GROUP BY  `s`.`contractID`;