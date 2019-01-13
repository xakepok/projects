alter table `#__prc_items` modify `price_rub` decimal(9,2) default 0 not null comment 'Цена в рублях (начальная)';
alter table `#__prc_items` modify `price_usd` decimal(9,2) default 0 not null comment 'Цена в долларах (начальная)';
alter table `#__prc_items` modify `price_eur` decimal(9,2) default 0 not null comment 'Цена в евро (начальная)';
