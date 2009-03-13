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
\set log_error_verbosity terse

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

-- Load Fixtures data
\i unit_testdata.sql
-- Plan the tests.
SELECT plan(1);

-- Run the tests.
SELECT pass( 'My test passed, w00t!' );

-- Finish the tests and clean up.
SELECT * FROM finish();


--DROP SCHEMA "unittest" CASCADE;
ROLLBACK;