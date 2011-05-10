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
    $imports = Doctrine::getTable('imports')->findAll(); 
    foreach($imports as $import) 
    {
      if($import->getState() == 'imported')
      {     
        imports::importDataToTable($import) ;      
        $q = Doctrine_Query::create()
          ->update('imports p')
          ->set('p.state','\'processing\'')
          ->where('p.id=?',$import->getId())
          ->execute() ;
      }
    }
  }
}  
