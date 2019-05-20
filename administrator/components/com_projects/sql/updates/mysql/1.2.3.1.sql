alter table `#__prj_contracts`
    add doc_status tinyint default 0 not null after status;

create index `#__prj_contracts_doc_status_index`
    on `#__prj_contracts` (doc_status);

