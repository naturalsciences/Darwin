<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(5, new lime_output_color());
$properties = Doctrine::getTable('CatalogueProperties')->findForTable('taxonomy',4);
$t->is( count($properties) , 2, 'There is properties for this table / record_id');
$t->is( $properties[0]->getPropertyType() , 'physical measurement', 'ordered correcty');
$t->is( count($properties[0]->PropertiesValues) , 2, 'There is also 2 properties values');

$types = Doctrine::getTable('CatalogueProperties')->getDistinctType();
$t->is( count($types) , 2, 'There is 2 different type');
$t->is( $types[1]->getType() , 'protection status', 'the and is accessible through getType');