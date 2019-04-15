create or replace view `#__prj_exhibitors_all` as
select
    `e`.`id`,
    CONCAT(IFNULL(`title_ru_short`,IFNULL(`title_ru_full`,IFNULL(`title_en`,''))),' (',`reg`.`name`,')') as `exhibitor`
from `#__prj_exp` as `e`
         left join `#__grph_cities` as `reg` on `e`.`regID` = `reg`.`id`;

create table `#__api_keys`
(
    id int auto_increment,
    api_key varchar(100) not null,
    constraint `#__api_keys_pk`
        primary key (id)
)
    comment 'Ключи доступа API';

create unique index `#__api_keys_api_key_uindex`
    on `#__api_keys` (api_key);

alter table `#__prj_exp`
    add user_id int default null null comment 'ID учётной записи';

alter table `#__prj_exp`
    add constraint `#__prj_exp_#__users_id_fk`
        foreign key (user_id) references `#__users` (id);

