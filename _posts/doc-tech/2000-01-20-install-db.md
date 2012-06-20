---
layout: page
title: Install DaRWIN Database on Debian
menu: doc
group: doc-tech-en
---
{% include JB/setup %}

Install the Db on Debian (Squeeze or Wheezy)
============================================

Here is a how to for the installation of the Postgresql DB on a Debian
Squeeze or Wheezy (Stable at the time of writing).
We’re gonna describe the installation for either a PostgreSQL 8.4 or a
PostgreSQL 9.1.

PostgreSQL 8.4
--------------

### Packages installation

Begin by installing the postgresql server and make (used for
installation)

{% highlight bash %}
# aptitude install  make git postgresql-8.4 postgresql-contrib-8.4 postgresql-8.4-hstore-new postgresql-8.4-postgis
{% endhighlight %}

PostgreSQL 9.1
--------------

### Packages installation

At the difference of Pg 8.4, at the time of writing it hasn’t been
possible, on Debian Wheezy, to install postgis by packages. The
installation of postgis has to be done “by hand” and be compiled.
So that’s why, in this case, a huge package list is needed:

{% highlight bash %}
# aptitude install  make git postgresql-9.1 postgresql-contrib-9.1 postgresql-9.1-postgis
{% endhighlight %}



Configuration of Pg
-------------------

Quick, Dirty and Unsafe config of PG:

in `/etc/postgresql/8.4/main/postgresql.conf`


{% highlight cfg %}
 listen_addresses = '*'
 custom_variable_classes = 'darwin'
 datestyle = 'iso, dmy'
{% endhighlight %}

in `/etc/postgresql/8.4/main/pg_hba.conf`

{% highlight sh %}
  host all all 0.0.0.0 0.0.0.0 md5
{% endhighlight %}

Don’t forget to restart or at least check your cluster if your changes
to the config file are in effect.

Get the Code
-------------------


If your db is on the same machine as your web, just go to the  `data/db/` directory.
if not, just do :

{% highlight bash %}
 $ git clone http://projects.naturalsciences.be/code/darwin/web.git
{% endhighlight %}

then go to the `web/data/db/` directory

Installation of Darwin in Pg
----------------------------

First of all contributive packages are used in Darwin such as pg_trgm
and pgcrypto

We provide a make script for the installation of darwin

use the command make or make help to view more details about the make
file.

For some commands you must be connected to the database with the
postgres user account, so let’s login:

{% highlight bash %}
 # sudo -su postgres
{% endhighlight %}


We STRONGLY recommend you to put a `~/.pgpass` file in your home
directory to avoid typing your password over and over again (especially
for testing). [Read the Documentation about .pgpass](http://www.postgresql.org/docs/8.4/interactive/libpq-pgpass.html)

Add a `.pgpass` and make it look (please adapt it for your needs) like this :

{% highlight sh %}
 127.0.0.1:*:*:darwin2:MyP4ssw0rd!
{% endhighlight %}

 Don't forget to chmod it correctly

{% highlight bash %}
 $ chmod u=rw ~/.pgpass
{% endhighlight %}

The easiest way to install the db is to call the command :

With Postgresql  v__8.4__ (default) do

{% highlight bash %}
$ make install-all
{% endhighlight %}

With other versions of postgres issue the previous command with the
parameter __PG_VER__ equal the value of the postgres version targeted

For instance for a postgres version __8.3__ you would issue the following
command:

{% highlight bash %}
$ make install-all PG_VER=8.3
{% endhighlight %}

… and for a postgres version 9.1 you would issue the following command:

{% highlight bash %}
$ make install-all PG_VER=9.1
{% endhighlight %}

This command will create a new db with a new tablespace and install all
of darwin in it.

You can also customize the installation by passing some variable to make
(see make help)


