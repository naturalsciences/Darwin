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
      new sfCommandOption('reset', null, sfCommandOption::PARAMETER_NONE, 'delete current data in the database for this user'),
      // add your own options here
    ));

    $this->namespace        = 'darwin';
    $this->name             = 'add-widgets';
    $this->briefDescription = 'Add default widgets for a user';
    $this->detailedDescription = <<<EOF
The [darwin:add-widgets|INFO] task insert all widgets for an user.

Example:
  [php symfony darwin:add-widgets 1|INFO]

If you want the task to remove existing data in the database for this user,
use the [--reset|COMMENT] option:

  [./symfony darwin:add-widgets --reset|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

	$user = Doctrine::getTable('Users')->find($arguments['user_id']);
	
	if($options['reset'])
	{
	  Doctrine_Query::create()
             ->delete('MyPreferences p') 
			 ->where('p.user_ref = ?', $user->getId())
			 ->execute();
	  $this->logSection('add-widgets', sprintf('Remove old widgets successfully!',$cnt));
	}
    $cnt = $user->addUserWidgets();
    $this->logSection('add-widgets', sprintf('Added %d widgets successfully!',$cnt));

  }
}
