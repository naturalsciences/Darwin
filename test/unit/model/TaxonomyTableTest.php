<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(6, new lime_output_color());

$taxs = Doctrine::getTable('Taxonomy')->findByName('Falco Peregrinus eliticus');
$t->info('findWithParents($id)');
$taxa = Doctrine::getTable('Taxonomy')->findWithParents($taxs[0]->getId());
$t->isnt($taxa,null, 'we got a taxa');
$t->is($taxa->count(),2, 'we all parent of the taxa');
$t->is($taxa[0]->getId(),$taxs[0]->getKingdomRef(), 'Parent is correct');

$t->is($taxa[0]->Level->__toString(),'kingdom', 'get Level');
$t->is($taxa[0]->getNameWithFormat(),'Falco Peregrinus eliticus', 'get Name without extinct');

$taxa[0]->setExtinct('true');

$t->is($taxa[0]->getNameWithFormat(),'Falco Peregrinus eliticus  †', 'get Name without extinct');
