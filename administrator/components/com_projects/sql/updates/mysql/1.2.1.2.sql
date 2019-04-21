alter table `#__prj_stands_advanced`
    add columnID tinyint default 1 not null comment 'Номер колонки';

alter table `#__prj_stands_advanced` modify columnID tinyint default 1 not null comment 'Номер колонки' after itemID;

create unique index `#__prj_stands_advanced_standID_itemID_columnID_uindex`
    on `#__prj_stands_advanced` (standID, itemID, columnID);

drop index `#__prj_stands_advanced_standID_itemID_uindex` on `#__prj_stands_advanced`;

alter table `#__prj_stands`
    add columnID tinyint default 1 not null comment 'ID колонки' after itemID;

create or replace view `#__prj_contract_item_values` as
    select `s`.`itemID`                                                AS `itemID`,
           `s`.columnID as `columnID`,
           `s`.`contractID`                                            AS `contractID`,
           if((`inf`.`tip` = 1), count(`s`.`id`), sum(`cat`.`square`)) AS `value`,
           if((`inf`.`tip` = 1),
              sum((to_days(`s`.`department`) - to_days(`s`.`arrival`))),
              NULL)                                                    AS `value2`
    from ((((`#__prj_stands` `s` left join `#__prj_contracts` `c` on ((`c`.`id` = `s`.`contractID`))) left join `#__prj_contract_info` `inf` on ((`inf`.`contractID` = `s`.`contractID`))) left join `#__prj_catalog` `cat` on ((`cat`.`id` = `s`.`catalogID`)))
             left join `#__prc_items` `pi` FORCE INDEX (PRIMARY)
                       on ((`pi`.`id` = `s`.`itemID`)))
    where ((`pi`.`is_sq` = 1) and (`c`.`id` is not null))
    group by `s`.`itemID`, `columnID`, `s`.`contractID`
    union
    select `ci`.`itemID`     AS `itemID`,
           `ci`.`columnID` as `columnID`,
           `ci`.`contractID` AS `contractID`,
           `ci`.`value`      AS `value`,
           `ci`.`value2`     AS `value2`
    from ((`#__prj_contract_items` `ci` left join `#__prj_contracts` `c` on ((`c`.`id` = `ci`.`contractID`)))
             left join `#__prc_items` `pi` FORCE INDEX (PRIMARY)
                       on ((`pi`.`id` = `ci`.`itemID`)))
    where ((`pi`.`is_sq` = 0) and
           (`pi`.`is_electric` = 0) and
           (`pi`.`is_internet` = 0) and
           (`pi`.`is_multimedia` = 0) and
           (`pi`.`is_water` = 0) and
           (`pi`.`is_cleaning` = 0) and
           (`c`.`id` is not null))
    union
    select `a`.`itemID`     AS `itemID`,
           `a`.`columnID` as `columnID`,
           `s`.`contractID` AS `contractID`,
           sum(`a`.`value`) AS `value`,
           NULL             AS `value2`
    from (`#__prj_stands_advanced` `a`
             left join `#__prj_stands` `s` on ((`s`.`id` = `a`.`standID`)))
    group by `a`.`itemID`, `columnID`, `s`.`contractID`;

create or replace view `#__prj_stat` as
select `i`.`itemID`                                                                                                 AS `itemID`,
       `v`.`columnID` as `columnID`,
       `i`.`contractID`                                                                                             AS `contractID`,
       `p`.`is_sq`                                                                                                  AS `is_sq`,
       ifnull(`p`.`title_ru`, `p`.`title_en`)                                                                       AS `title`,
       `c`.`currency`                                                                                               AS `currency`,
       round(((case
                   when (`c`.`currency` = 'rub') then `p`.`price_rub`
                   when (`c`.`currency` = 'usd') then `p`.`price_usd`
                   when (`c`.`currency` = 'eur') then `p`.`price_eur` end) * (case
                                                                                  when (`i`.`columnID` = '1')
                                                                                      then `p`.`column_1`
                                                                                  when (`i`.`columnID` = '2')
                                                                                      then `p`.`column_2`
                                                                                  when (`i`.`columnID` = '3')
                                                                                      then `p`.`column_3` end)),
             2)                                                                                                     AS `cost`,
       ifnull(round(`v`.`value`, 2), 0)                                                                             AS `value`,
       round(`v`.`value2`, 2)                                                                                       AS `value2`,
       round((100 - (100 * `i`.`factor`)), 0)                                                                       AS `factor`,
       round(((`i`.`markup` * 100) - 100), 0)                                                                       AS `markup`,
       round(((((((case
                       when (`c`.`currency` = 'rub') then `p`.`price_rub`
                       when (`c`.`currency` = 'usd') then `p`.`price_usd`
                       when (`c`.`currency` = 'eur') then `p`.`price_eur` end) *
                  if((`inf`.`tip` = 0), `v`.`value`, 1)) * ifnull(`v`.`value2`, 1)) * (case
                                                                                           when (`i`.`columnID` = '1')
                                                                                               then `p`.`column_1`
                                                                                           when (`i`.`columnID` = '2')
                                                                                               then `p`.`column_2`
                                                                                           when (`i`.`columnID` = '3')
                                                                                               then `p`.`column_3` end)) *
               ifnull(`i`.`markup`, 1)) - (((((case
                                                   when (`c`.`currency` = 'rub') then `p`.`price_rub`
                                                   when (`c`.`currency` = 'usd') then `p`.`price_usd`
                                                   when (`c`.`currency` = 'eur') then `p`.`price_eur` end) *
                                              if((`inf`.`tip` = 0), `v`.`value`, 1)) * ifnull(`v`.`value2`, 1)) * (case
                                                                                                                       when (`i`.`columnID` = '1')
                                                                                                                           then `p`.`column_1`
                                                                                                                       when (`i`.`columnID` = '2')
                                                                                                                           then `p`.`column_2`
                                                                                                                       when (`i`.`columnID` = '3')
                                                                                                                           then `p`.`column_3` end)) *
                                           if(isnull(`i`.`factor`), 1, (1 - `i`.`factor`)))),
             2)                                                                                                     AS `price`
from ((((`#__prj_contract_item_values` `v` left join `#__prj_contract_items` `i` on ((
        (`v`.`contractID` = `i`.`contractID`) and
        (`v`.`itemID` = `i`.`itemID`) and (`v`.`columnID` = `i`.`columnID`)))) left join `#__prc_items` `p` on ((`p`.`id` = `i`.`itemID`))) left join `#__prj_contracts` `c` on ((`c`.`id` = `i`.`contractID`)))
         left join `#__prj_contract_info` `inf` on ((`inf`.`contractID` = `i`.`contractID`)))
where (`c`.`id` is not null);

create or replace view `#__prj_stat_items_values` as
select `itemID`, `contractID`, sum(`value`) as `value`, sum(`price`) as `price`, `currency`
from `#__prj_stat`
group by `itemID`, `contractID`;

create unique index `#__prj_contract_items_itemID_columnID_contractID_uindex`
    on `#__prj_contract_items` (itemID, columnID, contractID);

create or replace view `#__prj_contract_amounts` as
select `contractID` AS `contractID`,
       sum(`price`) AS `price`,
       `currency`   AS `currency`
from `#__prj_stat_items_values`
group by `contractID`;

