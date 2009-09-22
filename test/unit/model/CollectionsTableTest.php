<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(6, new lime_output_color());

$t->info('fetchByInstitutionList');
$list = Doctrine::getTable('Collections')->fetchByInstitutionList();

$t->is($list[0]->getFormatedName(),'Institut Royal des Sciences Naturelles de Belgique','Thre list give institutions');
$collections = $list[0]->Collections;

$t->is($collections->count(),4,'Get all collections');
$t->is($collections[0]->getPath(),'/','The first item has path /');
$t->is($collections[3]->getName(),'Fossile Aves','The childrens item are also fetched');

$t->is($list[1]->getFormatedName(),'UGMM','Thre list give institutions');

$collections = $list[1]->Collections;

$t->is($collections[0]->getName(),'Molusca','The last item is molusca (correctly order)');
