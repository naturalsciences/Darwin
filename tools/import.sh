#/bin/sh

#script launched by incron 
# $1 parameter is the file with the absolute path to be imported via the task below
php /var/project/darwin/web/symfony darwin:process-import
