<?php

class darwinImportTask extends sfBaseTask
{

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine')
      ));      
    $this->namespace        = 'darwin';
    $this->name             = 'process-import';
    $this->briefDescription = 'Import uploaded file to potgresql temp table';
    $this->detailedDescription = <<<EOF
Nothing
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
     // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $conn = Doctrine_Manager::connection();
    $conn->getDbh()->exec('BEGIN TRANSACTION');
    do  
    {
      $id = $conn->fetchOne('SELECT get_import_rows()');
      if($id != null) 
      {
        $q = Doctrine_Query::create()
          ->from('imports p')
          ->where('p.id=?',$id)
          ->fetchOne() ;
         imports::importDataToTable($q) ; 
      }
    }
    while($id != null);
    $conn->getDbh()->exec('COMMIT TRANSACTION');
  }
}  
