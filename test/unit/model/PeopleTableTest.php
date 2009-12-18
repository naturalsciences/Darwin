<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(3, new lime_output_color());
$p = Doctrine::getTable('People')->searchPysical('Duchesne');
$t->is(count($p),2,'2 record for this criteria');
$p = Doctrine::getTable('People')->searchPysical('Poilux');
$t->is(count($p),1,'there is only one Poilux \o/');
$p = Doctrine::getTable('People')->searchPysical('ugmm');
$t->is(count($p),0,'there is no physical UGMM');
