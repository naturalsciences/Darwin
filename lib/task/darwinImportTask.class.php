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
    $conn->getDbh()->exec('BEGIN TRANSACTION;');
    do  
    {
      // il faudra que j'update ma ligne en mettant state = processing avant de la verouiller
      $id = $conn->fetchOne('SELECT get_import_rows()');      
      if($id != null) 
      {
        $q = Doctrine_Query::create()
          ->from('imports p')
          ->where('p.id=?',$id)
          ->fetchOne() ;
        $file = sfConfig::get('sf_upload_dir').'/uploaded_'.sha1($q->getFilename().$q->getCreatedAt()).'.xml' ;    
        if(file_exists($file))
        {
          $import = new importDnaXml() ;
          $import->importFile($file,$id) ;
          Doctrine_Query::create()
            ->update('imports p')
            ->set('p.state',"'ok'")
            ->where('p.id = ?', $id)
            ->execute();
        }              
      }
    }
    while($id != null);
    $conn->getDbh()->exec('COMMIT TRANSACTION;');    
  }
}  
