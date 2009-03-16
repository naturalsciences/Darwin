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
\i tests_fixtures.sql



--DROP SCHEMA "unittest" CASCADE;
--ROLLBACK;