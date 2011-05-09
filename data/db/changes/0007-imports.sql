create sequence imports_seq;

create table imports
  (
    id integer not null default nextval('preferences_id_seq'),
    user_ref integer not null,
    format varchar not null,
    collection_ref integer not null,
    filename varchar not null,
    state varchar not null default '',
    created_at timestamp not null default now(),
    updated_at timestamp,
    constraint fk_imports_collections foreign key (collection_ref) references collections(id) on delete cascade
    constraint fk_imports_users foreign key (user_ref) references users(id) on delete cascade          
  );

comment on table imports is 'Table used to check the state of the date coming from an uploaded file';
comment on column imports.user_ref is 'The referenced user id';
comment on column imports.format is 'The import template to use for the imported file';
comment on column imports.filename is 'The filename of the file to proceed';
comment on column imports.collection_ref is 'The collection associated';
comment on column imports.state is 'the state of the processing the file';
comment on column imports.created_at is 'Creation of the file';
comment on column imports.updated_at is 'When the data has been modified lately';


