<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(9, new lime_output_color());

$fromDate = new FuzzyDateTime('1830/01/01', 32);
$toDate = new FuzzyDateTime('2009/12/31', 32);
$igs = Doctrine::getTable('Igs')->getIgLike("",$fromDate, $toDate)->execute();
$t->is( $igs->count() , 4, 'There are "4" igs in fixtures');
$t->is( $igs[0]->getIgNum() , '10795', 'ordered correcty: "10795" first');
$t->is( $igs[3]->getIgNum() , '3881', 'ordered correcty: "3881" Expedition last');
$igs = Doctrine::getTable('Igs')->getIgLike('',$fromDate, $toDate, 'ig_num', 'desc')->execute();
$t->is( $igs[3]->getIgNum() , '10795', 'ordered correcty: "10795" last');
$t->is( $igs[0]->getIgNum() , '3881', 'ordered correcty: "3881" Expedition first');
$igs = Doctrine::getTable('Igs')->getIgLike('',$fromDate, $toDate, 'ig_date', 'asc')->execute();
$t->is( $igs[0]->getIgNum() , '21Ter', 'ordered correcty: "21Ter" first');
$t->is( $igs[3]->getIgNum() , '10795', 'ordered correcty: "10795" Expe last');
$fromDate = new FuzzyDateTime('1935/01/01', 32);
$toDate->setMask(0);
$igs = Doctrine::getTable('Igs')->getIgLike('',$fromDate, $toDate)->execute();
$t->is( count($igs) , 1, 'There are "1" igs which answer criterias');
$toDate = new FuzzyDateTime('1936/12/31', 32);
$fromDate->setMask(0);
$igs = Doctrine::getTable('Igs')->getIgLike('',$fromDate, $toDate)->execute();
$t->is( count($igs) , 4, 'There are "4" igs which answer criterias');