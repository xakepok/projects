alter table `#__prj_todos`
    add `notify_group` int default null null comment 'Группа уведомлений';

create index `#__prj_todos_notify_group_index`
    on `#__prj_todos` (`notify_group`);

create index `#__prj_todos_is_notify_notify_group_index`
    on `#__prj_todos` (is_notify, notify_group);

