alter table `#__prj_activities`
       add for_contractor tinyint default 0 not null;

create index `#__prj_activities_for_contractor_index`
       on `#__prj_activities` (for_contractor);

update `#__prj_activities` set `for_contractor` = 1 where `title` LIKE '%ПОДРЯДЧИК%';