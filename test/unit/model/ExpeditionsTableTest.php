<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(2, new lime_output_color());

$fromDate = new FuzzyDateTime('1975/01/01');
$toDate = new FuzzyDateTime('2009/12/31');
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate)->execute();
$t->is( count($expeditions) , 8, 'There are 8 expeditions in fixtures');
$t->is( $expeditions[0]->getName() , 'Antarctica 1988', 'ordered correcty');