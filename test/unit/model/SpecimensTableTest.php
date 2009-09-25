<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(3, new lime_output_color());

$t->info('getAcquisitionsCategories');
$cat = SpecimensTable::getDistinctCategories();
$t->is(count($cat),12,'Number of differents categories');
$t->is($cat['Undefined'],'Undefined','get the first category');
$t->is($cat['Collect'],'Collect','get the last category');