alter table `#__prc_items` modify `column_1` decimal(3,2) default 1.00 not null comment 'Коэффициент наценки в колонке 1';
alter table `#__prc_items` modify `column_2` decimal(3,2) default 1.5 not null comment 'Коэффициент наценки в колонке 2';
alter table `#__prc_items` modify `column_3` decimal(3,2) default 2.00 not null comment 'Коэффициент наценки в колонке 2';
alter table `#__prj_contract_items` modify `factor` decimal(3,2) default 1 not null comment 'Множитель первой цены';
alter table `#__prj_contract_items` modify `markup` decimal(3,2) null comment 'Наценка за позиционирование';
alter table `#__prj_contract_items` modify `value` decimal(9,2) not null comment 'Значение пункта из прайс-листа';
alter table `#__prj_contract_items` modify `value2` decimal(9,2) null comment 'Значение из второй единицы измерения';
alter table `#__prj_scores` modify `amount` decimal(9,2) not null comment 'Сумма платежа';
alter table `#__prj_payments` modify `amount` decimal(9,2) default 0 not null comment 'Сумма';