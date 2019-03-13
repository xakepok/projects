alter table `#__prc_items`
  add is_cleaning tinyint default 0 not null comment 'Представляет собой поле площади уборки' after is_markup;

alter table `#__prc_items`
  add is_internet tinyint default 0 not null comment 'Представляет собой способ подключения к интернету' after is_electric;

alter table `#__prc_items`
  add is_water tinyint default 0 not null comment 'Представляет собой кол-во подводов воды' after is_sq;

alter table `#__prc_items`
  modify in_stat tinyint default 1 not null comment 'Участвует ли в статистике' after is_water;

alter table `#__prc_items`
  add is_multimedia tinyint default 0 not null comment 'Представляет собой мультимедиа' after is_internet;

