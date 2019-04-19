alter table `#__prc_items`
    add stop tinyint default 0 not null comment 'Пункт не доступен для заказа';

create index `#__prc_items_stop_index`
    on `#__prc_items` (stop);

