alter table `mfmza_prj_catalog_titles`
  add tip tinyint default 0 not null comment ' 0 - стенд, 1 - номер ' after id;