<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(9, new lime_output_color());

$fromDate = new FuzzyDateTime('1975/01/01');
$toDate = new FuzzyDateTime('2009/12/31');
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate)->execute();
$t->is( count($expeditions) , 8, 'There are 8 expeditions in fixtures');
$t->is( $expeditions[0]->getName() , 'Antarctica 1988', 'ordered correcty: Antarctica 1988 first');
$t->is( $expeditions[7]->getName() , 'Pollux Expedition', 'ordered correcty: Pollux Expedition last');
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate, 'name', 'desc')->execute();
$t->is( $expeditions[7]->getName() , 'Antarctica 1988', 'ordered correcty: Antarctica 1988 last');
$t->is( $expeditions[0]->getName() , 'Pollux Expedition', 'ordered correcty: Pollux Expedition first');
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate, 'expedition_from_date', 'asc')->execute();
$t->is( $expeditions[0]->getName() , 'Cathy Expe', 'ordered correcty: Cathy Expe first');
$t->is( $expeditions[7]->getName() , 'Mister B Expe', 'ordered correcty: Mister B Expe last');
$fromDate = new FuzzyDateTime('1988/01/01');
$fromDate->setMask(32);
$toDate->setMask(0);
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate)->execute();
$t->is( count($expeditions) , 5, 'There are 5 expeditions which answer criterias');
$toDate = new FuzzyDateTime('1988/12/31');
$fromDate->setMask(0);
$toDate->setMask(32);
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate)->execute();
$t->is( count($expeditions) , 3, 'There are 3 expeditions which answer criterias');