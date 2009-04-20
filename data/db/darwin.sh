#!/bin/bash
netaddr=192.168.20.117
if [ "${1:-'null'}" == "-d" ]; then
  dbname=${2:-'darwin2'}
else
  dbname=${1:-'darwin2'}
fi
importuser='darwin1'
clusterpath='/var/lib/postgresql/8.3/darwin/'
dbpath=$clusterpath$dbname
mkdir $dbpath
createlang -d template1 plpgsql
createlang -d template1 plpythonu
if [ "${1:-'null'}" == "-d" ]; then
  psql -q -v dbname=$dbname -f dropdb.sql
fi
psql -q -v dbpath=\'$dbpath\' -v dbname=$dbname -f createdb.sql
dropuser -q $dbname
dropuser -q $importuser
createuser -l -i -q -S -R -D -P -E $dbname
createuser -l -i -q -S -R -D -P -E $importuser
psql -q -d $dbname -v dbname=$dbname -f createschema.sql
psql -q -d $dbname -v dbname=$dbname -f create_testschema.sql
psql -q -d $dbname -v importuser=$importuser -f create_darwin1_schema.sql
psql -q -d $dbname -v dbname=$dbname -f lib/accent.sql
psql -q -h $netaddr -d $dbname -U $dbname -f createdomains.sql
psql -q -h $netaddr -d $dbname -U $dbname -f createtypes.sql
psql -q -h $netaddr -d $dbname -U $dbname -f createtables.sql
psql -q -h $netaddr -d $dbname -U $dbname -f createindexes.sql
psql -q -h $netaddr -d $dbname -U $dbname -f initiate_data.sql
psql -q -h $netaddr -d $dbname -U $dbname -f createfunctions.sql
psql -q -h $netaddr -d $dbname -U $dbname -f createtriggers.sql
psql -q -h $netaddr -d $dbname -U $dbname -f addchecks.sql

