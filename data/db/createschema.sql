comment on role :dbname is 'Main user';
create schema authorization :dbname ;
create schema unittest authorization :dbname ;
comment on schema :dbname is 'Main collection management tool schema';
comment on schema unittest is 'Schema for tests purposes only';
