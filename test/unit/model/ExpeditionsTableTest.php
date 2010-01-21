<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(11, new lime_output_color());

$fromDate = new FuzzyDateTime('1975/01/01', 32);
$toDate = new FuzzyDateTime('2009/12/31', 32);
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate)->execute();
$t->is( $fromDate->getMask(), 32, 'From date filter mask correct: 32');
$t->is( $toDate->getMask(), 32, 'To date filter mask correct: 32');
$t->is( count($expeditions) , 4, 'There are "4" expeditions in fixtures');
$t->is( $expeditions[0]->getName() , 'Antarctica 1988', 'ordered correcty: "Antarctica 1988" first');
$t->is( $expeditions[3]->getName() , 'Pollux Expedition', 'ordered correcty: "Pollux Expedition" last');
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate, 'name', 'desc')->execute();
$t->is( $expeditions[3]->getName() , 'Antarctica 1988', 'ordered correcty: "Antarctica 1988" last');
$t->is( $expeditions[0]->getName() , 'Pollux Expedition', 'ordered correcty: "Pollux Expedition" first');
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate, 'expedition_from_date', 'asc')->execute();
$t->is( $expeditions[0]->getName() , 'PNG77', 'ordered correcty: "PNG77" first');
$t->is( $expeditions[3]->getName() , 'Mister B Expe', 'ordered correcty: "Mister B" Expe last');
$fromDate = new FuzzyDateTime('1988/01/01', 32);
$toDate->setMask(0);
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate)->execute();
$t->is( count($expeditions) , 5, 'There are "5" expeditions which answer criterias');
$toDate = new FuzzyDateTime('1988/12/31', 32);
$fromDate->setMask(0);
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate)->execute();
$t->is( count($expeditions) , 3, 'There are "3" expeditions which answer criterias');