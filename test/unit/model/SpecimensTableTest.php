<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(11, new lime_output_color());

$t->info('getDistinctCategories');
$cat = SpecimensTable::getDistinctCategories();
$t->is(count($cat),12,'Number of differents categories: "12"');
$t->is($cat['Undefined'],'Undefined','get the first category: "Undefined"');
$t->is($cat['Collect'],'Collect','get the last category: "Collect"');

$t->info('getDistinctTools');
$cat = Doctrine::getTable('Specimens')->getDistinctTools();
$t->is($cat->count(),3,'Number of differents tools: "3"');
$t->is($cat[1]->getTool(),'Fish Net','get the first tool: "Fish Net"');
$t->is($cat[2]->getTool(),'fish Pas net','get the last tool: "fish Pas net"');

$t->info('getDistinctMethods');
$cat = Doctrine::getTable('Specimens')->getDistinctMethods();
$t->is($cat->count(),3,'Number of differents method: "3"');
$t->is($cat[1]->getMethod(),'Fishing','get the first method: "Fishing"');
$t->is($cat[2]->getMethod(),'Hunting','get the last method: "Hunting"');

$t->info('getDistinctHostRelationships');
$cat = Doctrine::getTable('Specimens')->getDistinctHostRelationships();
$t->is($cat->count(),2,'Number of differents host relationships: "2"');
$t->is($cat[0]->getHostRelationship(),'Symbiosis','get the first host relationship: "Symbiosis"');
