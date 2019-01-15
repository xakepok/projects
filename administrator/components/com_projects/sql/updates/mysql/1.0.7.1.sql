create table `#__prj_catalog_titles`
(
  `id` int auto_increment,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci not null comment 'Название каталога',
  constraint `#__prj_catalog_titles_pk`
    primary key (id)
)
  comment 'Список каталогов стендов';
INSERT INTO `#__prj_catalog_titles` (id, title) VALUES (1, 'Первый каталог');

alter table `#__prj_catalog`
  add titleID int default 1 not null after id;

create index `#__prj_catalog_titleID_index`
  on `#__prj_catalog` (titleID);

drop index number on `#__prj_catalog`;

create unique index number
  on `#__prj_catalog` (number, titleID);

alter table `#__prj_catalog`
  add constraint `#__prj_catalog_#__prj_catalog_titles_id_fk`
    foreign key (titleID) references `#__prj_catalog_titles` (id)
      on update cascade on delete cascade;
