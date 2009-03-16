\set ECHO
\set QUIET 1
-- Turn off echo and keep things quiet.

-- Format the output for nice TAP.
\pset format unaligned
\pset tuples_only true
\pset pager

-- Revert all changes on failure.
\set ON_ERROR_ROLLBACK 1
\set ON_ERROR_STOP true
\set QUIET 1

BEGIN;

-- CREATE SCHEMA "unittest";
SET search_path TO "unittest"; 
SET client_min_messages TO warning; -- notice;


-- Load test functions
\i lib/pgtap.sql
-- Load type,tables, functions and triggers
\i createtypes.sql
\i createdomains.sql
\i createtables.sql
\i createfunctions.sql
\i createtriggers.sql
\i initiate_data.sql

-- Load Fixtures data
\i tests/fixtures.sql

SELECT plan(7);

insert into specimens (id, collection_ref) VALUES (1,1);
select ok( 0 = (SELECT count(*) FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=1) ,'No code inserted if collection not incremented');

insert into specimens (id, collection_ref) VALUES (2,2);

select ok( 1 = (SELECT count(*) FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=2),'First code instered' );
select ok( 1 = (SELECT code FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=2),'1st code created' );



insert into specimens (id, collection_ref, category) VALUES (3,2,'figurate');

select ok( 1 = (SELECT count(*) FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=3),'2e code inserted' );
select ok( 2 = (SELECT code FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=3),'2e code incremented' );

UPDATE specimens_codes SET code_prefix='cds-', code_suffix='mol' WHERE code_category= 'main' AND specimen_ref=3;

insert into specimens (id, collection_ref, category) VALUES (4,2,'observation');
select ok( 'cds-' = (SELECT code_prefix FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=4),'prefix copied' );
select ok( 'mol' = (SELECT code_suffix FROM specimens_codes WHERE code_category = 'main' AND specimen_ref=4),'suffix copied' );


SELECT * FROM specimens_codes WHERE code_category = 'main'; --DEBUG

-- Finish the tests and clean up.
SELECT * FROM finish();


--DROP SCHEMA "unittest" CASCADE;
ROLLBACK;