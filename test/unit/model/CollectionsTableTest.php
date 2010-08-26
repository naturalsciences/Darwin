<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(12, new lime_output_color());

$t->info('fetchByInstitutionList');
$list = Doctrine::getTable('Collections')->fetchByInstitutionList();
$user_id = Doctrine::getTable('users')->getUserByPassword('root','evil')->getId() ;

$t->is($list[0]->getFormatedName(),'Institut Royal des Sciences Naturelles de Belgique','Thre list give institutions');
$collections = $list[0]->Collections;

$t->is(count($collections),4,'Get all collections');
$t->is($collections[0]->getPath(),'/','The first item has path /');
$t->is($collections[3]->getName(),'Fossile Aves','The childrens item are also fetched');

$t->is($list[1]->getFormatedName(),'UGMM','Thre list give institutions');

$collections = Doctrine::getTable('Collections')->getAllCollections();

$t->is($collections[0]->getName(),'Molusca','The last item is molusca (correctly order)');

$t->info('getCollectionByName');

$col = Doctrine::getTable('Collections')->getCollectionByName('Molusca');
$t->is($col->getId(), $collections[0]->getId(),'Got the good collection');

$t->info('getDistinctCollectionByInstitution');

$list2 = Doctrine::getTable('Collections')->getDistinctCollectionByInstitution($list[0]->getId());
$t->is(count($list2), 5, 'We have 5 collections in this institution ("" + 4)');

$list2 = Doctrine::getTable('Collections')->getDistinctCollectionByInstitution($list[1]->getId());
$t->is(count($list2), 2, 'We have 2 collections in this institution ("" + 1)');

$t->info('findCollectionsByUser');

$rights = Doctrine::getTable('CollectionsRights')->findCollectionsByUser($user_id);
$t->is(count($rights), 1, 'Root have encoder right on 1 collections');

$collections = $list[0]->Collections;
$list3 = Doctrine::getTable('Collections')->fetchByCollectionParent($collections[0]->getId());
$t->is(count($list3), 3, 'Vertebrates have 3 children collections ');

$collection = Doctrine::getTable('Collections')->findOneByName('Vertebrates');
$collectionId = $collection->getId();
$value = Doctrine::getTable('Collections')->getAndUpdateLastCode($collectionId);
$t->is($value, 1, 'Updated value of "code_last_value" for collection "Vertebrates" is well "1"');