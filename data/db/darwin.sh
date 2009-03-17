#!/bin/bash
netaddr=192.168.20.117
if [ "${1:-'null'}" == "-d" ]; then
  dbname=${2:-'darwin2'}
else
  dbname=${1:-'darwin2'}
fi
clusterpath='/var/lib/postgresql/8.3/darwin/'
dbpath=$clusterpath$dbname
mkdir $dbpath
createlang -d template1 plpgsql
createlang -d template1 plpythonu
if [ "${1:-'null'}" == "-d" ]; then
  psql -q -v dbname=$dbname -f dropdb.sql
fi
psql -q -v dbpath=\'$dbpath\' -v dbnameS=\'$dbname\' -v dbname=$dbname -f createdb.sql
dropuser -q $dbname
createuser -l -i -q -S -R -D -P -E $dbname
psql -q -d $dbname -v dbname=$dbname -f createschema.sql
psql -q -h $netaddr -d $dbname -U $dbname -f createdomains.sql
psql -q -h $netaddr -d $dbname -U $dbname -f createtypes.sql
psql -q -h $netaddr -d $dbname -U $dbname -f createtables.sql
psql -q -h $netaddr -d $dbname -U $dbname -f initiate_data.sql
