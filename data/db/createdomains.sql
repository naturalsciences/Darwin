create domain genders as char(1) 
       constraint genders_chk
                  check (VALUE in ('M', 'F'));
create domain full_text_language as varchar default 'simple' not null
       constraint full_text_language_chk
                  check (VALUE in ('danish', 'dutch', 'english', 'finnish', 'french', 'german', 'hungarian', 'italian', 'norwegian', 'portuguese', 'romanian', 'russian', 'spanish', 'swedish', 'turkish', 'simple'));
create domain update_date_time as timestamp default now() not null;
create domain classifications_ids as integer default 0 not null;
create domain classifications_names as tsvector default '' not null;
