alter table `#__prj_catalog_titles`
  add tip tinyint default 0 not null comment ' 0 - стенд, 1 - номер ' after id;

create table `#__prj_hotels`
(
  id int auto_increment,
  title_ru text null,
  title_en text default null null,
  constraint `#__prj_hotels_pk`
    primary key (id)
)
  comment 'Отели';

insert into `#__prj_hotels`
(`id`, `title_ru`)
VALUES
(1, 'Комплекс отдыха Бекасово-spa'),
(2, 'Les Art Resolt'),
(3, 'Дом отдыха Покровское'),
(4, 'Heliopark'),
(5, 'Парк-отель Ершово');

create table `#__prj_hotels_number_categories`
(
  id int auto_increment,
  hotelID int not null comment 'ID отеля',
  title_ru text not null comment 'Название категории',
  title_en text default null null comment 'Category title',
  constraint `#__prj_hotels_number_categories_pk`
    primary key (id),
  constraint `#__prj_hotels_number_categories_#__prj_hotels_id_fk`
    foreign key (hotelID) references `#__prj_hotels` (id)
      on update cascade on delete cascade
)
  comment 'Категории номеров в отелях';

create index `#__prj_hotels_number_categories_hotelID_index`
  on `#__prj_hotels_number_categories` (hotelID);

insert into `#__prj_hotels_number_categories`
(`id`, `hotelID`, `title_ru`)
values
(NULL, '1', 'Стандарт однокомнатный'),
(NULL, '1', 'Студия'),
(NULL, '1', 'Двухкомнатный номер'),
(NULL, '1', 'Трёхкомнатный номер апартамент'),
(NULL, '1', 'Стандарт мансардный однокомнатный'),
(NULL, '1', 'Двухкомнатный номер люкс'),
(NULL, '1', 'Трёхкомнатный номер дуплекс'),
(NULL, '1', 'Коттедж гостинный дом - двухкомнатный номер 37 кв. м.'),
(NULL, '1', 'Коттедж гостинный дом - двухкомнатный номер люкс 53 кв. м.'),
(NULL, '1', 'Отдельный коттедж'),
(NULL, '2', 'Стандарт'),
(NULL, '2', 'Симпл сьют'),
(NULL, '2', 'Панорама сьют'),
(NULL, '2', 'Классик сьют+'),
(NULL, '2', 'Villa'),
(NULL, '3', 'Корпус №2 (VIP)'),
(NULL, '3', 'Корпус №1 (после ремонта)'),
(NULL, '3', 'Корпус №3'),
(NULL, '3', 'Таун-хаус №1'),
(NULL, '3', 'Villa'),
(NULL, '4', 'Стандарт 11 кв. м.'),
(NULL, '4', 'Стандарт 11-14 кв. м.'),
(NULL, '4', 'Стандарт расширенный'),
(NULL, '4', 'Полулюкс'),
(NULL, '5', 'Полулюкс'),
(NULL, '5', 'Люкс двухкомнатный');

alter table `#__prj_catalog`
  add categoryID int default NULL null comment 'ID категории номера (для номеров)';

alter table `#__prj_catalog`
  add title text default NULL null comment 'Название номера (для номеров в отеле)';

create index `#__prj_catalog_categoryID_index`
  on `#__prj_catalog` (categoryID);

alter table `#__prj_catalog`
  add constraint `#__prj_catalog_#__prj_hotels_number_categories_id_fk`
    foreign key (categoryID) references `#__prj_hotels_number_categories` (id)
      on update cascade on delete cascade;

alter table `#__prj_catalog` modify `number` varchar(10) default NULL null comment 'Номер стенда';

alter table `#__prj_catalog` modify `square` float default NULL null comment 'Площадь стенда';
