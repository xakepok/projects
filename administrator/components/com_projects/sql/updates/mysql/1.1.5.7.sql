create or replace view `#__prj_rep_todos_by_dates` as
select `c`.`prjID`                             AS `projectID`,
       `p`.`title_ru`                          AS `project`,
       `t`.`managerID`                         AS `managerID`,
       `u`.`name`                              AS `manager`,
       week(`t`.`dat`, 0)                      AS `week`,
       date_format(`t`.`dat`, '%d.%m.%Y (%a)') AS `dat`,
       ifnull(count(`t`.`id`), 0)              AS `cnt`
from (((`#__prj_todos` `t` left join `#__prj_contracts` `c` on ((`c`.`id` = `t`.`contractID`))) left join `#__prj_projects` `p` on ((`p`.`id` = `c`.`prjID`)))
       left join `#__users` `u` on ((`u`.`id` = `t`.`managerID`)))
where ((`t`.`dat` between (now() + interval -(2) week) and now()) and (`t`.`is_notify` = 0))
group by `c`.`prjID`, `t`.`managerID`, `t`.`dat`;

