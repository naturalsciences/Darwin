---
layout: page
title: Install DaRWIN Interface on Debian
menu: doc
group: doc-tech-en
---
{% include JB/setup %}

Install the web interface
=========================

The procces is describe for a Debian (lenny) and other debian like
system (ubuntu,…)

Begin with the dependency :

{% highlight bash %}
# aptitude install apache2 php5 php5-cli php5-pgsql php5-xdebug php-apc php5-imagick php5-ldap
{% endhighlight %}

And don’t forget to [set up your database](01-install-db.html).

check you have writing rights on `/var/www/`

and there, checkout the code :

{% highlight bash %}
 git clone http://projects.naturalsciences.be/code/darwin/web.git
{% endhighlight %}

Go into /var/www/web and checkout the following code:

{% highlight bash %}
 git submodule init
 git submodule update
{% endhighlight %}


(now, remove writing rights on the `/var/www` directory if you want)

and configure the database connection :

{% highlight bash %}
cp config/databases.yml.init config/databases.yml
{% endhighlight %}


and settings :

{% highlight bash %}
cp config/darwin.yml.init config/darwin.yml
{% endhighlight %}

(and the config/app.yml if you wish)

and edit the `databases.yml` set your host / username / password .


Rem.: By default the public part of the application gets connected to
the database with the same user who’s got right to do CRUD actions.
 If you wish to have the read only user to be used for the public part
of the application connection, please do:

{% highlight bash %}
cp apps/public/config/databases.yml.init apps/public/config/databases.yml
{% endhighlight %}


and edit the `databases.yml` to set the host / read only username / read
only user password.

Don’t forget to add the cache directory and the log directory:

{% highlight bash %}
 mkdir /var/www/web/cache
 mkdir /var/www/web/log
{% endhighlight %}

Then, configure your apache often in `/etc/apache2/sites-enabled/000-default`

and set :

{% highlight apache %}
            DocumentRoot /var/www/web/web
            DirectoryIndex index.php
            Alias /sf /var/www/web/lib/vendor/symfony/data/web/sf/
            <Directory "/var/www/web/lib/vendor/symfony/data/web/sf">
                    AllowOverride All
                    Allow from All
            </Directory>
            <Directory "/var/www/">
                AllowOverride All
                Allow from All
            </Directory>
{% endhighlight %}

add also the rewrite capabilities to apache :

{% highlight bash %}
  sudo a2enmod rewrite
{% endhighlight %}


and don’t forget to restart apache :

{% highlight bash %}
 sudo /etc/init.d/apache2 restart
{% endhighlight %}

and fix the permissions for symfony in the `/var/www/web/` :

{% highlight bash %}
php symfony project:permission
{% endhighlight %}

You can then add the first administrator with the command :

{% highlight bash %}
 php symfony darwin:add-admin
{% endhighlight %}

The task ask you for a given name, family name, login, and password.
It add also every widgets for the user.

Application configuration :
===========================

You also may want to configure some settings in `config/darwin.yml` like http proxy settings or others...


Configure Php :
===========================

Modify the php config in the file `/etc/php5/apache2.php.ini` :

{% highlight ini %}
    file_uploads = On
    upload_max_filesize = 10M



    memory_limit = 128M; At least 128M

{% endhighlight %}


Additional required tool :
==========================

At this step, darwin will work for a testing or demo environement but you need to add some
configurations to automate some tasks

## For Import module

You'll need incron to react to a new file import and a cron to check rows

Install the software :

{% highlight bash %}
  sudo aptitude install incron
{% endhighlight %}

Edit `/etc/incron.allow` file and add www-data

Create /etc/incron.d/import file and add the line below :

{% highlight bash %}
    /var/www/web/uploads IN_CREATE sh /var/www/web/tools/import.sh
{% endhighlight %}


Create `/etc/cron.hourly/check-import` file and add the line below :

{% highlight bash %}
    php /var/www/web/symfony darwin:check-import --do-import 2>/var/www/web/log/import_log_error.log
{% endhighlight %}


## Multimedia temporary file management

In order to delete all files that are older than X hours, add a cron
file in `/etc/cron.d/clean_tmp_upload`

and add this line

{% highlight bash %}
*  */2   * * *   root  find /http/darwin/uploads/multimedia/temp -mmin +120 -delete
{% endhighlight %}



## To retrieve news from twitter (Caution ...subject to change)

Add file in `/etc/cron.d/fetch_news`

{% highlight bash %}
*/10 * * * *     root /http/darwin/tools/fetch_feed.sh > /dev/null 2>&1
{% endhighlight %}
