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
Wheezy (Stable at the time of writing).
We’re gonna describe the installation With Postgresql 9.1.
As Darwin is tested with PG9.1 it may be working with newer version as well.

PostgreSQL 9.1
--------------

### Packages installation

Install the required packages :

{% highlight bash %}
# sudo aptitude install git postgresql-9.1 postgresql-contrib-9.1 postgresql-9.1-postgis
{% endhighlight %}



Configuration of Pg
-------------------

Quick, Dirty and Unsafe config of PG (where *main* is the name of your cluster)

in `/etc/postgresql/9.1/main/postgresql.conf`


{% highlight cfg %}
listen_addresses = '*'
custom_variable_classes = 'darwin'
datestyle = 'iso, dmy'
{% endhighlight %}

you might want to display everything in your logs by adding  : log_min_duration_statement = 0

in `/etc/postgresql/9.1/main/pg_hba.conf`

{% highlight sh %}
host all all 0.0.0.0 0.0.0.0 md5
{% endhighlight %}

Don’t forget to restart with your cluster ( sudo /etc/init.d/postgresql restart )


Get the Code
-------------------


Darwin Db install scripts are located in the main darwin repository (where the web interface is ).
If you already have a copy of the code, go in it, if not, just do :

{% highlight bash %}
 $ git clone https://github.com/naturalsciences/Darwin.git darwin
{% endhighlight %}

then go to the `darwin/data/db/` directory

Installation of Darwin in Pg
----------------------------

First of all contributive packages are used in Darwin such as pg_trgm
and pgcrypto

We provide a script for the installation of darwin

use the command install.sh help to view more details about the script.

For some commands you must be connected to the database with the
postgres user account, so make sure you have access to the postgres user through sudo


We STRONGLY recommend you to put a `~/.pgpass` file in your home and in the postgres home
directory to avoid typing your password over and over again (especially
for testing).

[Read the Documentation about .pgpass](http://www.postgresql.org/docs/9.1/interactive/libpq-pgpass.html)

Add a `.pgpass` and make it look (please adapt it for your needs) like this :

{% highlight sh %}
 127.0.0.1:*:*:darwin2:MyP4ssw0rd!
{% endhighlight %}

 Don't forget to chmod it correctly

{% highlight bash %}
 $ chmod u=rw ~/.pgpass
{% endhighlight %}

The easiest way to install the db is to call the command :

{% highlight bash %}
$ ./install.sh install-all
{% endhighlight %}

This command will create a new db with a new tablespace and install all
of darwin in it.

You can also customize the installation by passing some variable to the script
(see ./install.sh help)
