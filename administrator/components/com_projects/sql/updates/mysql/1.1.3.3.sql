create view `#__prj_tmp_acts` as
SELECT `c`.`id` as `contractID`, `c`.`expID`, `act`.`title`, `r`.`id` as `rubricID`
FROM `#__prj_contracts` as `c`
       LEFT JOIN `#__prj_exp_act` as `a` ON `a`.`id` = `c`.`expID`
       LEFT JOIN `#__prj_activities` as `act` ON `act`.`id` = `a`.`actID`
       LEFT JOIN `#__prj_rubrics` as `r` ON `r`.`title` = `act`.`title`
WHERE `r`.`id` IS NOT NULL;

INSERT INTO `#__prj_contract_rubrics` (`contractID`, `rubricID`) SELECT `contractID`, `rubricID` FROM `#__prj_tmp_acts`;

DROP VIEW `#__prj_tmp_acts`;