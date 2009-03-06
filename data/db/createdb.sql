create tablespace :dbname location :dbpath ;
create database :dbname template DEFAULT tablespace :dbname;
comment on tablespace :dbname is 'Collection management tool tablespace';
comment on database :dbname is 'Collection management tool database';
