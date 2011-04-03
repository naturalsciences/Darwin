<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(5, new lime_output_color());

$t->info('getDistinctCategories');
$cat = SpecimensTable::getDistinctCategories();
$t->is(count($cat),12,'Number of differents categories: "12"');
$t->is($cat['undefined'],'Undefined','get the first category: "Undefined"');
$t->is($cat['collect'],'Collect','get the last category: "Collect"');

$t->info('getDistinctHostRelationships');
$cat = Doctrine::getTable('Specimens')->getDistinctHostRelationships();
$t->is($cat->count(),1,'Number of differents host relationships: "1"');
$t->is($cat[0]->getHostRelationship(),'Symbiosis','get the first host relationship: "Symbiosis"');
