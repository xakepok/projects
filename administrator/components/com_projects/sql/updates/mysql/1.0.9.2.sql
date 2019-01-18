alter table `#__prj_scores` modify `amount` decimal(11,2) not null comment 'Сумма платежа';
alter table `#__prj_payments` modify `amount` decimal(11,2) default 0 not null comment 'Сумма';
alter table `#__prc_items` modify `price_rub` decimal(11,2) default 0.00 not null comment 'Цена в рублях (начальная)';