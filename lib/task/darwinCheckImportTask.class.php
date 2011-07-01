<?php

class darwinCheckImportTask extends sfBaseTask
{

  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),      
      new sfCommandOption('do-import', null, sfCommandOption::PARAMETER_NONE, 'if some lines are marked as "to be imported", try to import after the check'),
      new sfCommandOption('id', null, sfCommandOption::PARAMETER_REQUIRED, 'Only do the job for a given import id'),
      ));      
    $this->namespace        = 'darwin';
    $this->name             = 'check-import';
    $this->briefDescription = 'check staging lines status and/or import them into real tables';
    $this->detailedDescription = <<<EOF
Nothing
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    if(!empty($options['id']) && ! ctype_digit($options['id']) )
    {
      $this->logSection('id not int', sprintf('the Id parameter must be an integer (id of import)'),null, 'ERROR') ;
    }
     // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $environment = $this->configuration instanceof sfApplicationConfiguration ? $this->configuration->getEnvironment() : $options['env']; 
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    $conn = Doctrine_Manager::connection();
    // First Check :)
    $sql = 'select fct_imp_checker_manager(s.*) from staging s';
    if(!empty($options['id']))
      $sql.= " WHERE import_ref = ".$options['id'];
    $this->logSection('checking', sprintf('Start checking staging'));
    $conn->getDbh()->exec($sql);
    
    if(empty($options['do-import']))
      return;

    //Then if option is set, do Import
    $conn->getDbh()->exec('BEGIN TRANSACTION;');

    if(isset($options['id']))
    {
      $sql = 'select fct_importer_dna('.$options['id'].')';
      $conn->getDbh()->exec($sql);
    }
    else
    {
      $this->logSection('fetch', sprintf('Load Imports file in processing state'));
      $imports  = Doctrine::getTable('Imports')->getWithImports();
      foreach($imports as $import)
      {
        $this->logSection('Processing', sprintf('Start processing import %d',$import->getId()));
        $sql = 'select fct_importer_dna('.$import->getId().')';
        $conn->getDbh()->exec($sql);
      }
    }
    $conn->getDbh()->exec('COMMIT;');
  }
}  
