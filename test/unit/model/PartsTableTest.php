<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(8, new lime_output_color());

$t->info('Specimen Parts');
$storages = Doctrine::getTable('SpecimenParts')->getDistinctContainerStorages('BROL');
$t->is($storages, array('dry'=>'dry'),'Only dry to storage');
$storages = Doctrine::getTable('SpecimenParts')->getDistinctSubContainerStorages('Choze');
$t->is($storages, array('dry'=>'dry'),'Only dry to storage');

$individual = Doctrine::getTable('SpecimenIndividuals')->findOneBySpecimenRef(4);
$part = new SpecimenParts();
$part->setSpecimenIndividualRef($individual->getId());
$part->setContainer('container');
$part->setContainerStorage('cont store');
$part->setSubContainer('container');
$part->setSubContainerStorage('subcont store');
$part->save();

$storages = Doctrine::getTable('SpecimenParts')->getDistinctContainerStorages('container');
$t->is(count($storages), 2,'new storage');
$storages = Doctrine::getTable('SpecimenParts')->getDistinctSubContainerStorages('container');
$t->is(count($storages), 2,'new storage');

$storages = Doctrine::getTable('SpecimenParts')->getDistinctContainerTypes();
$t->is(count($storages), 2,'wa got a storage');
$storages = Doctrine::getTable('SpecimenParts')->getDistinctSubContainerTypes();
$t->is(count($storages), 2,'wa got a storage');

$parts = Doctrine::getTable('SpecimenParts')->findForIndividual($individual->getId());
$t->is(count($parts),1,'Get All parts for individuals');

$part = new SpecimenParts();
$part->setSpecimenIndividualRef($individual->getId());
$part->setContainer('container2');
$part->setContainerStorage('cont store');
$part->setSubContainer('container');
$part->setSubContainerStorage('subcont store');
$part->save();
$parts = Doctrine::getTable('SpecimenParts')->findForIndividual($individual->getId());
$t->is(count($parts),2,'Get All parts for individuals');