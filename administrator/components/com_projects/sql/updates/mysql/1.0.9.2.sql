alter table `#__prj_scores` modify `amount` decimal(11,2) not null comment 'Сумма платежа';
alter table `#__prj_payments` modify `amount` decimal(11,2) default 0 not null comment 'Сумма';
alter table `#__prc_items` modify `price_rub` decimal(11,2) default 0.00 not null comment 'Цена в рублях (начальная)';
alter table `#__prc_items` modify `unit` set('piece', 'sqm', 'kit', 'letter', 'pair', 'sym', 'pm', 'days', 'hours', 'nights', '4h', '1d1sqm', '1sqm1p', '1s1sqm', 'view', '1pd', 'ppl') default 'piece' not null comment 'Единица измерения';
alter table `#__prc_items` modify `unit_2` set('piece', 'sqm', 'kit', 'letter', 'pair', 'sym', 'pm', 'days', 'hours', 'nights', '4h', '1d1sqm', '1sqm1p', '1s1sqm', 'view', '1pd', 'ppl') null comment 'Вторая единица измерения';