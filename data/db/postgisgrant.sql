GRANT USAGE ON SCHEMA postgis TO :dbname;
ALTER ROLE :dbname SET search_path TO "$user", postgis, public;
ALTER ROLE postgres SET search_path TO "$user", postgis, public;
