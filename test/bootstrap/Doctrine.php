<?php
// test/bootstrap/Doctrine.php
include(dirname(__FILE__).'/unit.php');
 
$configuration = ProjectConfiguration::getApplicationConfiguration( 'frontend', 'test', true);
 

new sfDatabaseManager($configuration);

$conn = Doctrine_Manager::connection();
$conn->exec("SELECT nextval('taxonomy_id_seq')");
$conn->exec("SELECT setval('taxonomy_id_seq',10000)");
$conn->exec("SELECT nextval('expeditions_id_seq')");
$conn->exec("SELECT setval('expeditions_id_seq',10000)");
Doctrine::loadData(sfConfig::get('sf_test_dir').'/fixtures');

