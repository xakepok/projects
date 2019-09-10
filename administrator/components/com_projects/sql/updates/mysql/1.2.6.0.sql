create or replace view `s7vi9_prj_contract_todos_count` as
select contractID, ifnull(count(id),0) as cnt
from `s7vi9_prj_todo_list`
where (is_notify = 0 and state = 0)
group by contractID;

create or replace view `s7vi9_prj_contracts_min_dates` as
select contractID, min(dat) as dat
from `s7vi9_prj_todos`
where state = 0 and is_notify = 0
group by contractID;

create or replace view `s7vi9_prj_contracts_v2` as
select c.id, if(p.contract_prefix is null, ifnull(c.number,c.number_free), concat(p.contract_prefix, ifnull(c.number,c.number_free))) as num,
       c.dat, c.currency,
       c.isCoExp, c.parentID,
       p.title_ru as project, p.id as projectID,
       ifnull(e.title_ru_short,ifnull(e.title_ru_full,e.title_en)) as exhibitor, e.id as exhibitorID,
       e.title_ru_short, e.title_ru_full, e.title_en,
       ifnull(coexp.title_ru_short,ifnull(coexp.title_ru_full,coexp.title_en)) as parent,
       ifnull(tdc.cnt,0) as todos,
       u.name as manager, c.managerID,
       s.title as status, s.weight as status_weight, c.status as status_code,
       IF(`c`.`currency`='rub',0,IF(`c`.`currency`='usd',1,2)) as `sort_amount`,
       c.doc_status,
       ifnull(a.price,0) as amount,
       ifnull(pay.payments,0) as payments,
       ifnull(a.price,0)-ifnull(pay.payments,0) as debt,
       c.payerID, ifnull(e1.title_ru_short,ifnull(e1.title_ru_full,e1.title_en)) as payer,
       cmd.dat as plan_dat
from `s7vi9_prj_contracts` as `c`
         left join `s7vi9_prj_projects` `p` on c.prjID = `p`.id
         left join `s7vi9_prj_exp` `e` on c.expID = `e`.id
         left join `s7vi9_prj_exp` `coexp` on coexp.id = `c`.parentID
         left join `s7vi9_prj_contract_todos_count` `tdc` on `tdc`.contractID = `c`.`id`
         left join `s7vi9_users` `u` on c.managerID = `u`.id
         left join `s7vi9_prj_statuses` `s` on `s`.`code` = `c`.`status`
         left join `s7vi9_prj_contract_amounts` `a` on c.id = `a`.contractID
         left join `s7vi9_prj_contract_payments` `pay` on c.id = `pay`.contractID
         left join `s7vi9_prj_exp` `e1` on `e1`.id = `c`.`payerID`
         left join `s7vi9_prj_contracts_min_dates` `cmd` on c.id = `cmd`.contractID;

create table `s7vi9_prj_user_settings`
(
    id int auto_increment,
    userID int not null,
    params text default null null comment 'JSON настроек',
    constraint `s7vi9_prj_user_settings_pk`
        primary key (id),
    constraint `s7vi9_prj_user_settings_s7vi9_users_id_fk`
        foreign key (userID) references `s7vi9_users` (id)
            on update cascade on delete cascade
)
    comment 'Настройки пользователей';

create unique index `s7vi9_prj_user_settings_userID_uindex`
    on `s7vi9_prj_user_settings` (userID);

