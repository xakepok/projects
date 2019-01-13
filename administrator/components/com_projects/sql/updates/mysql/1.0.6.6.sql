alter table `#__prc_items` modify `price_rub` decimal(9,2) default 0 not null comment 'Цена в рублях (начальная)';
alter table `#__prc_items` modify `price_usd` decimal(9,2) default 0 not null comment 'Цена в долларах (начальная)';
alter table `#__prc_items` modify `price_eur` decimal(9,2) default 0 not null comment 'Цена в евро (начальная)';
create or replace view #__prj_contract_amounts as
select `c`.`id`                                                                                       AS `contractID`,
       sum(round((((((`i`.`price_rub` * `v`.`value`) * ifnull(`v`.`value2`, 1)) * (case
                                                                                     when (`v`.`columnID` = '1')
                                                                                       then `i`.`column_1`
                                                                                     when (`v`.`columnID` = '2')
                                                                                       then `i`.`column_2`
                                                                                     when (`v`.`columnID` = '3')
                                                                                       then `i`.`column_3` end)) *
                   ifnull(`v`.`markup`, 1)) - ((((`i`.`price_rub` * `v`.`value`) * ifnull(`v`.`value2`, 1)) * (case
                                                                                                                 when (`v`.`columnID` = '1')
                                                                                                                   then `i`.`column_1`
                                                                                                                 when (`v`.`columnID` = '2')
                                                                                                                   then `i`.`column_2`
                                                                                                                 when (`v`.`columnID` = '3')
                                                                                                                   then `i`.`column_3` end)) *
                                               if(isnull(`v`.`factor`), 1, (1 - `v`.`factor`)))), 2)) AS `amount_rub`,
       sum(round((((((`i`.`price_usd` * `v`.`value`) * ifnull(`v`.`value2`, 1)) * (case
                                                                                     when (`v`.`columnID` = '1')
                                                                                       then `i`.`column_1`
                                                                                     when (`v`.`columnID` = '2')
                                                                                       then `i`.`column_2`
                                                                                     when (`v`.`columnID` = '3')
                                                                                       then `i`.`column_3` end)) *
                   ifnull(`v`.`markup`, 1)) - ((((`i`.`price_usd` * `v`.`value`) * ifnull(`v`.`value2`, 1)) * (case
                                                                                                                 when (`v`.`columnID` = '1')
                                                                                                                   then `i`.`column_1`
                                                                                                                 when (`v`.`columnID` = '2')
                                                                                                                   then `i`.`column_2`
                                                                                                                 when (`v`.`columnID` = '3')
                                                                                                                   then `i`.`column_3` end)) *
                                               if(isnull(`v`.`factor`), 1, (1 - `v`.`factor`)))), 2)) AS `amount_usd`,
       sum(round((((((`i`.`price_eur` * `v`.`value`) * ifnull(`v`.`value2`, 1)) * (case
                                                                                     when (`v`.`columnID` = '1')
                                                                                       then `i`.`column_1`
                                                                                     when (`v`.`columnID` = '2')
                                                                                       then `i`.`column_2`
                                                                                     when (`v`.`columnID` = '3')
                                                                                       then `i`.`column_3` end)) *
                   ifnull(`v`.`markup`, 1)) - ((((`i`.`price_eur` * `v`.`value`) * ifnull(`v`.`value2`, 1)) * (case
                                                                                                                 when (`v`.`columnID` = '1')
                                                                                                                   then `i`.`column_1`
                                                                                                                 when (`v`.`columnID` = '2')
                                                                                                                   then `i`.`column_2`
                                                                                                                 when (`v`.`columnID` = '3')
                                                                                                                   then `i`.`column_3` end)) *
                                               if(isnull(`v`.`factor`), 1, (1 - `v`.`factor`)))), 2)) AS `amount_eur`
from ((`#__prj_contract_items` `v` left join `#__prc_items` `i` on ((`i`.`id` = `v`.`itemID`)))
       left join `#__prj_contracts` `c` on ((`c`.`id` = `v`.`contractID`)))
group by `v`.`contractID`;

