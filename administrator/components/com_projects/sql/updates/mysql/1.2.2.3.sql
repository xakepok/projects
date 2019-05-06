alter table `#__prj_catalog`
    add gos_number varchar(10) default null null;

alter table `#__prj_catalog`
    drop column gos_number_1;

