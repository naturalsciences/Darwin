<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(13, new lime_output_color());

$taxs = Doctrine::getTable('Taxonomy')->findOneByName('Falco Peregrinus eliticus');
$t->info('findWithParents($id)');
$taxa = Doctrine::getTable('Taxonomy')->findWithParents($taxs->getId());
$t->isnt($taxa,null, 'we got a taxa');
$t->is($taxa->count(),3, 'we got all parent of the taxa');
$t->is($taxa[1]->getId(),$taxs->getParentRef(), 'Parent is correct');

$t->is($taxa[1]->Level->__toString(),'kingdom', 'get Level');
$t->is($taxs->getNameWithFormat(),'Falco Peregrinus eliticus', 'get Name without extinct');

$taxs->setExtinct('true');

$t->is($taxs->getNameWithFormat(),'Falco Peregrinus eliticus †', 'get Name without extinct');

$t->is(DarwinTable::getFilterForTable('classification_syonymies'),"ClassificationSyonymiesFormFilter",'Filter Form name');
$t->is(DarwinTable::getFormForTable('classification_syonymies'),"ClassificationSyonymiesForm",'Form Name');
$t->is(DarwinTable::getModelForTable('classification_syonymies'),"ClassificationSyonymies",'Model Name');

$t->is(Doctrine::getTable('Taxonomy')->findExcept(4)->toArray(),true,'We got the record with findExcept');
$t->is(Doctrine::getTable('Taxonomy')->findExcept(-1)->toArray(),true,'Record bellow 0 are found  with findExcept');
$t->is(Doctrine::getTable('Taxonomy')->findExcept(0),false,'Record 0 cannot be found with findExcept');
$t->is(Doctrine::getTable('Taxonomy')->findById(0)->toArray(),true,'Prove the record 0 can be found by an other mean (findById)');