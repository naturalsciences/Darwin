TESTS = $(wildcard tests/*.sql)
REGRESS = $(patsubst tests/%.sql,%,$(TESTS))
HOST = 127.0.0.1
DB_USER = darwin2

test:
	/usr/lib/postgresql/8.3/bin/pg_prove -h $(HOST) -U $(DB_USER) $(TESTS)
