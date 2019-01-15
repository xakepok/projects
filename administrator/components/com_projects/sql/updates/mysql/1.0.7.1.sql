create table `#__prj_catalog_titles`
(
  `id` int auto_increment,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci not null comment 'Название каталога',
  constraint `#__prj_catalog_titles_pk`
    primary key (id)
)
  comment 'Список каталогов стендов';