TESTS = $(wildcard tests/*.sql)
HOST = 127.0.0.1
DB_USER = darwin2
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
