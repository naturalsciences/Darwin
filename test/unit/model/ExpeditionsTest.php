<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(12, new lime_output_color());

$fromDate = new FuzzyDateTime('1975/01/01', 32);
$toDate = new FuzzyDateTime('2009/12/31', 32);
$expeditions = Doctrine::getTable('Expeditions')->getExpLike('',$fromDate, $toDate)->execute();
$t->is( $expeditions[0]->getExpeditionFromDateMasked() , '<em>01/</em>04/1988', 'Correct from date masked for "Antarctica 1988"');
$t->is( $expeditions[0]->getExpeditionToDateMasked() , '<em>31/</em>07/1988', 'Correct to date masked for "Antarctica 1988"');
$t->is( $expeditions[0]->getExpeditionFromDate() , array('year'=>1988, 'month'=>04, 'day'=>'', 'hour'=>'', 'minute'=>'', 'second'=>''), 'Correct from date masked as array for "Antarctica 1988"');
$t->is( $expeditions[0]->getExpeditionToDate() , array('year'=>1988, 'month'=>07, 'day'=>'', 'hour'=>'', 'minute'=>'', 'second'=>''), 'Correct to date masked as array for "Antarctica 1988"');
$expeditions[0]->setExpeditionFromDate($fromDate);
$expeditions[0]->setExpeditionToDate($toDate);
$t->is( $expeditions[0]->getExpeditionFromDateMasked() , '<em>01/01/</em>1975', 'Correct from date masked for "Antarctica 1988"');
$t->is( $expeditions[0]->getExpeditionToDateMasked() , '<em>31/12/</em>2009', 'Correct to date masked for "Antarctica 1988"');
$t->is( $expeditions[0]->getExpeditionFromDate() , array('year'=>1975, 'month'=>'', 'day'=>'', 'hour'=>'', 'minute'=>'', 'second'=>''), 'Correct from date masked as array for "Antarctica 1988"');
$t->is( $expeditions[0]->getExpeditionToDate() , array('year'=>2009, 'month'=>'', 'day'=>'', 'hour'=>'', 'minute'=>'', 'second'=>''), 'Correct to date masked as array for "Antarctica 1988"');
$expeditions[0]->setExpeditionFromDate('1975/01/01');
$expeditions[0]->setExpeditionToDate('2009/12/31');
$t->is( $expeditions[0]->getExpeditionFromDateMasked() , '01/01/1975', 'Correct from date masked for "Antarctica 1988"');
$t->is( $expeditions[0]->getExpeditionToDateMasked() , '31/12/2009', 'Correct to date masked for "Antarctica 1988"');
$t->is( $expeditions[0]->getExpeditionFromDate() , array('year'=>1975, 'month'=>01, 'day'=>01, 'hour'=>'', 'minute'=>'', 'second'=>''), 'Correct from date masked as array for "Antarctica 1988"');
$t->is( $expeditions[0]->getExpeditionToDate() , array('year'=>2009, 'month'=>12, 'day'=>31, 'hour'=>'', 'minute'=>'', 'second'=>''), 'Correct to date masked as array for "Antarctica 1988"');