create or replace view #__prj_contract_stands as
select `s`.`id`         AS `id`,
       `s`.`contractID` AS `contractID`,
       `c`.`number`     AS `number`,
       `s`.`sq`         AS `sq`,
       `s`.`show`       AS `show`
from (`#__prj_stands` `s`
       left join `#__prj_catalog` `c` on ((`c`.`id` = `s`.`catalogID`)))
where ((`s`.`catalogID` is not null) and (`s`.`itemID` is not null));
