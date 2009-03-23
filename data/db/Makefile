TESTS = $(wildcard tests/*.sql)
HOST = 127.0.0.1
DB_USER = darwin2
DB_NAME = darwin2
TEST_CMD = pg_prove
PSQL = /usr/bin/psql
PG_PROVE = /usr/lib/postgresql/8.3/bin/pg_prove
test:
ifeq ($(TEST_CMD),pg_prove)
	$(PG_PROVE) -h $(HOST) -U $(DB_USER) $(TESTS)
else ifeq ($(TEST_CMD),psql)
	for file in $(TESTS) ; \
	do \
		$(PSQL) -h $(HOST) -U $(DB_USER) -f $$file ; \
	done
endif

install-db:
	
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f createdomains.sql
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f createtypes.sql
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f createtables.sql
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f createfunctions.sql
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f createtriggers.sql
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f addchecks.sql
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f initiate_data.sql

drop-db:
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f droptriggers.sql
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f dropfunctions.sql
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f droptables.sql
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f droptypes.sql
	$(PSQL) -q -h $(HOST) -U $(DB_USER) -d $(DB_NAME) -f dropdomains.sql

