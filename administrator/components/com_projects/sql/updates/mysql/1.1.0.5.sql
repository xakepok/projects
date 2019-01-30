alter table `#__prj_exp`
       add is_contractor tinyint default 0 not null after id;

create index `#__prj_exp_is_contractor_index`
       on `#__prj_exp` (is_contractor);