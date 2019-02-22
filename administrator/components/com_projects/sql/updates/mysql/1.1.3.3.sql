create view `#__prj_tmp_acts` as
SELECT `c`.`id` as `contractID`, `c`.`expID`, `act`.`title`, `r`.`id` as `rubricID`
FROM `#__prj_contracts` as `c`
       RIGHT JOIN `#__prj_exp_act` as `a` ON `a`.`exbID` = `c`.`expID`
       LEFT JOIN `#__prj_activities` as `act` ON `act`.`id` = `a`.`actID`
       LEFT JOIN `#__prj_rubrics` as `r` ON `r`.`title` collate utf8_general_ci = `act`.`title` collate utf8_general_ci
WHERE `r`.`id` IS NOT NULL AND `a`.`id` is not null;

INSERT INTO `#__prj_contract_rubrics` (`contractID`, `rubricID`) SELECT `contractID`, `rubricID` FROM `#__prj_tmp_acts`;

DROP VIEW `#__prj_tmp_acts`;