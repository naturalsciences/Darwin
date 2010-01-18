<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(8, new lime_output_color());

$t->info('findByNameLike($name,$level=null)');
$taxs = Doctrine::getTable('Taxonomy')->findByNameLike('euca');
$t->is($taxs->count(),1,'There are some results in taxo');
$t->is($taxs[0]->getName(),'Eucaryota','we get the good taxa');

$taxs = Doctrine::getTable('Taxonomy')->findByNameLike('euca',4);
$t->is($taxs->count(),0,'There are no results in taxo with phylum');

$taxs = Doctrine::getTable('Taxonomy')->findByNameLike('falc');
$t->is($taxs->count(),5,'There are 2 results in taxo');
$t->is($taxs[0]->getName(),'Falco Peregrinus', 'they are order');

$t->info('findWithParents($id)');
$taxa = Doctrine::getTable('Taxonomy')->findWithParents($taxs[1]->getId());
$t->isnt($taxa,null, 'whe got a taxa');
$t->is($taxa->count(),2, 'whe all parent of the taxa');
$t->is($taxa[1]->getId(),$taxs[1]->getId(), 'ending with self');
