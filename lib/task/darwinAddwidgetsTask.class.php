<?php

class darwinAddwidgetsTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('user_id', sfCommandArgument::REQUIRED, 'The user Id'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'darwin';
    $this->name             = 'add-widgets';
    $this->briefDescription = 'Add default widgets for a user';
    $this->detailedDescription = <<<EOF
The [darwin:add-widgets|INFO] task insert all widgets for an user.

Example:
  [php symfony darwin:add-widgets 1|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $cnt = Users::addUserWidgets($arguments['user_id']);
    $this->logSection('add-widgets', sprintf('Added %d widgets successfully!',$cnt));

  }
}
