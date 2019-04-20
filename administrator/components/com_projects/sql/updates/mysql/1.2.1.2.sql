alter table `s7vi9_prj_stands_advanced`
    add columnID tinyint default 1 not null comment 'Номер колонки';

alter table `s7vi9_prj_stands_advanced` modify columnID tinyint default 1 not null comment 'Номер колонки' after itemID;

create unique index `s7vi9_prj_stands_advanced_standID_itemID_columnID_uindex`
    on `s7vi9_prj_stands_advanced` (standID, itemID, columnID);

drop index `s7vi9_prj_stands_advanced_standID_itemID_uindex` on `s7vi9_prj_stands_advanced`;

alter table `s7vi9_prj_stands`
    add columnID tinyint default 1 not null comment 'ID колонки' after itemID;