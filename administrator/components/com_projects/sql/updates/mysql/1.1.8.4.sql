alter table `#__prj_exp`
  add `is_ndp` tinyint default 0 not null comment 'Компания является организатором НДП' after `is_contractor`;

