<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(4, new lime_output_color());

$t->info('fetchList');
$list = Doctrine::getTable('Collections')->fetchList();
$t->is($list->count(),5,'Get all collections');
$t->is($list[0]->getPath(),'/','The first item has path /');
$t->is($list[4]->getName(),'Molusca','The last item is molusca (correctly order)');
$t->is($list[3]->getName(),'Fossile Aves','The childrens item are also fetched');