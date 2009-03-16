TESTS = $(wildcard tests/*.sql)
HOST = 127.0.0.1
DB_USER = darwin2
TEST_CMD = /usr/lib/postgresql/8.3/bin/pg_prove

test:
ifeq ($(TEST_CMD),/usr/lib/postgresql/8.3/bin/pg_prove)
	$(TEST_CMD) -h $(HOST) -U $(DB_USER) $(TESTS)
else ifeq ($(TEST_CMD),psql)
	for file in $(TESTS) ; \
	do \
		$(TEST_CMD) -h $(HOST) -U $(DB_USER) -f $$file ; \
	done
endif
