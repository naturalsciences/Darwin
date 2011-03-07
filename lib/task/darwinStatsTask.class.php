<?php

class darwinStatsTask extends sfBaseTask
{
  private $request_array = array(
  "req1" => array(    
    "title" => "All individual types and the count associated",
    "description" => "",
    "help" => "",
    "request" => "SELECT individual_type, count(DISTINCT individual_type) as Count FROM darwin_flat Group by individual_type Order by individual_type",
    "expandable" => true,
    ),
  "req2" => array(    
    "title" => "All objects encoded in DaRWIN2",
    "description" => "",
    "help" => "",
    "request" => "SELECT count(id) as total from specimens",
    "expandable" => false,
    ),   
  ) ;
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
      ));
    $this->namespace        = 'darwin';
    $this->name             = 'gen-stats';
    $this->briefDescription = 'Generate statistics';
    $this->detailedDescription = <<<EOF
The [darwin:gen-stats|INFO] task launch all request stored to create
a .yml file with all request results
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();  
    foreach($this->request_array as $key=>$request)
    {
      $conn = $connection->getDbh();
      $statement = $conn->prepare($request['request']);
      $statement->execute(); 
    }
  }
}
