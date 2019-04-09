create or replace view `#__prj_contract_item_values` as
    SELECT
        `s`.`itemID` AS `itemID`,
        `s`.`contractID` AS `contractID`,
        IF((`inf`.`tip` = 1),
           COUNT(`s`.`id`),
           SUM(`cat`.`square`)) AS `value`,
        IF((`inf`.`tip` = 1),
           SUM((TO_DAYS(`s`.`department`) - TO_DAYS(`s`.`arrival`))),
           NULL) AS `value2`,
        'sq' AS `tip`
    FROM
        ((((`#__prj_stands` `s`
            LEFT JOIN `#__prj_contracts` `c` ON ((`c`.`id` = `s`.`contractID`)))
            LEFT JOIN `#__prj_contract_info` `inf` ON ((`inf`.`contractID` = `s`.`contractID`)))
            LEFT JOIN `#__prj_catalog` `cat` ON ((`cat`.`id` = `s`.`catalogID`)))
            LEFT JOIN `#__prc_items` `pi` force index (primary) ON ((`pi`.`id` = `s`.`itemID`)))
    WHERE
        ((`pi`.`is_sq` = 1)
            AND (`c`.`id` IS NOT NULL))
    GROUP BY `s`.`itemID` , `s`.`contractID`
    UNION SELECT
              `ci`.`itemID` AS `itemID`,
              `ci`.`contractID` AS `contractID`,
              `ci`.`value` AS `value`,
              `ci`.`value2` AS `value2`,
              'standart' AS `tip`
    FROM
        ((`#__prj_contract_items` `ci`
            LEFT JOIN `#__prj_contracts` `c` ON ((`c`.`id` = `ci`.`contractID`)))
            LEFT JOIN `#__prc_items` `pi` force index (primary) ON ((`pi`.`id` = `ci`.`itemID`)))
    WHERE
        ((`pi`.`is_sq` = 0)
            AND (`pi`.`is_electric` = 0)
            AND (`pi`.`is_internet` = 0)
            AND (`pi`.`is_multimedia` = 0)
            AND (`pi`.`is_water` = 0)
            AND (`pi`.`is_cleaning` = 0)
            AND (`c`.`id` IS NOT NULL))
    UNION SELECT
              `a`.`itemID` AS `itemID`,
              `s`.`contractID` AS `contractID`,
              SUM(`a`.`value`) AS `value`,
              NULL AS `value2`,
              'new' AS `tip`
    FROM
        (`#__prj_stands_advanced` `a`
            LEFT JOIN `#__prj_stands` `s` ON ((`s`.`id` = `a`.`standID`)))
    GROUP BY `a`.`itemID` , `s`.`contractID`;

create index `#__prj_stands_contractID_itemID_index`
    on `#__prj_stands` (contractID, itemID);

create index `#__prj_todos_state_is_notify_index`
    on `#__prj_todos` (state, is_notify);

create or replace view `#__prj_todos_by_contracts` as
select `contractID`, `id`
from `#__prj_todos`
where `state` = 0 and `is_notify` = 0
order by null;


