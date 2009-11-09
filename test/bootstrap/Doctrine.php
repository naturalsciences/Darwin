<?php
// test/bootstrap/Doctrine.php
include(dirname(__FILE__).'/unit.php');
 
$configuration = ProjectConfiguration::getApplicationConfiguration( 'frontend', 'test', true);
 

new sfDatabaseManager($configuration);

$conn = Doctrine_Manager::connection();
$conn->exec("SELECT nextval('taxonomy_id_seq')");

Doctrine::loadData(sfConfig::get('sf_test_dir').'/fixtures');

