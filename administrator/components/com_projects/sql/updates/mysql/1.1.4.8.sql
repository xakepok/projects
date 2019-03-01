CREATE OR REPLACE VIEW `#__prj_rep_statuses` AS
SELECT `c`.`prjID` as `projectID`, `p`.`title_ru` as `project`, `c`.`managerID`, `u`.`name` as `manager`, `c`.`status`, IFNULL(COUNT(`c`.`id`),0) as `cnt`
FROM
  `#__prj_contracts` as `c`
    LEFT JOIN `#__prj_projects` as `p` on `p`.`id` = `c`.`prjID`
    LEFT JOIN `#__users` as `u` on `u`.`id` = `c`.`managerID`
GROUP BY `c`.`prjID`, `c`.`managerID`, `c`.`status`;

CREATE OR REPLACE VIEW `#__prj_rep_todos_by_dates` AS
SELECT `c`.`prjID` as `projectID`, `p`.`title_ru` as `project`, `t`.`managerID`, `u`.`name` as `manager`, WEEK(`t`.`dat`) as `week`, `t`.`dat`, IFNULL(count(`t`.`id`),0) as `cnt`
FROM `#__prj_todos` as `t`
       LEFT JOIN `#__prj_contracts` as `c` on `c`.`id` = `t`.`contractID`
       LEFT JOIN `#__prj_projects` as `p` on `p`.`id` = `c`.`prjID`
       LEFT JOIN `#__users` as `u` on `u`.`id` = `c`.`managerID`
WHERE ((`t`.`dat` BETWEEN DATE_ADD(NOW(), INTERVAL -2 WEEK) AND NOW()) AND (`t`.`is_notify` = 0))
GROUP BY `c`.`prjID`, `t`.`managerID`, `t`.`dat`;

CREATE OR REPLACE VIEW `#__prj_rep_todos_by_weeks` AS
SELECT
  `projectID`, `project`, `managerID`, `manager`, `week`, SUM(`cnt`) as `todos`
FROM `#__prj_rep_todos_by_dates`
GROUP BY `projectID`, `manager`, `week`;