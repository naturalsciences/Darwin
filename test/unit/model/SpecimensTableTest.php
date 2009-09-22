<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(3, new lime_output_color());

$t->info('getAcquisitionsCategories');
$cat = Doctrine::getTable('Specimens')->getDistinctCategories();
$t->is($cat->count(),2,'Number of differents categories');
$t->is($cat[0]->getCategory(),'expedition','get the first category');
$t->is($cat[1]->getCategory(),'theft','get the last category');