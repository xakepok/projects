alter table `#__prj_todos`
  add `is_notify` tinyint default 0 not null after `id`;

create index `#__prj_todos_is_notify_index`
  on `#__prj_todos` (`is_notify`);