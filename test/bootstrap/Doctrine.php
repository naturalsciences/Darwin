<?php
// test/bootstrap/Doctrine.php
include(dirname(__FILE__).'/unit.php');
 
$configuration = ProjectConfiguration::getApplicationConfiguration( 'frontend', 'test', true);
 

new sfDatabaseManager($configuration);
DarwinTestFunctional::initiateDB();
Doctrine::loadData(sfConfig::get('sf_test_dir').'/fixtures');

