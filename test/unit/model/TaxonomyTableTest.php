<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(3, new lime_output_color());

$t->info('getByNameLike');
$taxs = Doctrine::getTable('Taxonomy')->getByNameLike('archaa');
$t->is($taxs->count(),1,'There are some results in taxo');
$t->is($taxs[0]->getName(),'Archaea','we get the good taxa');

$taxs = Doctrine::getTable('Taxonomy')->getByNameLike('archaa',4);
$t->is($taxs->count(),0,'There are no results in taxo with phylum');
