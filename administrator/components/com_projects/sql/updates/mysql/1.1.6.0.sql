create or replace view `#__prj_rep_todos_by_dates` as
select `c`.`prjID`                        AS `projectID`,
       `p`.`title_ru`                     AS `project`,
       `t`.`managerID`                    AS `managerID`,
       `u`.`name`                         AS `manager`,
       week(`t`.`dat`, 0)                 AS `week`,
       `t`.`dat`                          AS `dat`,
       IF(`t`.`dat` > current_date + interval +1 week, 1, 0) AS `is_future`,
       ifnull(count(`t`.`id`), 0)         AS `cnt`
from (((`#__prj_todos` `t` left join `#__prj_contracts` `c` on ((`c`.`id` = `t`.`contractID`))) left join `#__prj_projects` `p` on ((`p`.`id` = `c`.`prjID`)))
            left join `#__users` `u` on ((`u`.`id` = `t`.`managerID`)))
where ((`t`.`dat` between now() and now() + interval +1 year) and (`t`.`is_notify` = 0))
group by `c`.`prjID`, `t`.`managerID`, `t`.`dat`;