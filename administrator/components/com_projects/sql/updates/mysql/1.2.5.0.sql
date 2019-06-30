create or replace view `#__prj_profiles` as
select e.id, e.regID, e.regID_fact, e.tip, e.title_ru_short, e.title_ru_full, e.title_en,
       b.inn, b.kpp, b.rs, b.ks, b.bank, b.bik,
       city.name as city
from `#__prj_exp` e
         left join `#__prj_exp_bank` b on e.id = b.exbID
         left join `#__grph_cities` city on e.regID = city.id;

alter table `#__prj_contracts` alter column dat set default null;

alter table `#__prj_contracts`
    add userID int default null null comment 'ID пользователя для работы со сделкой в ЛКЭ';

alter table `#__prj_contracts`
    add constraint `#__prj_contracts_#__users_id_fk`
        foreign key (userID) references `#__users` (id);

