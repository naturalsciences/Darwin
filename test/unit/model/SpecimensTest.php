<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(3, new lime_output_color());

$toDate = new FuzzyDateTime('2009/12/31', 32);

$collection = Doctrine::getTable('Collections')->findOneByName('Vertebrates');
$specimen = new Specimens();
$specimen->setCollectionRef($collection['id']);
$specimen->setAcquisitionDate($toDate);
$specimen->save();

$specimens = Doctrine::getTable('Specimens')->findAll();
$t->is( $specimens->count() , 5, 'New "Vertebrate" specimen inserted');
$t->is( $specimens[4]->getAcquisitionDateMasked() , '<em>31/12</em>/2009', 'Correct date masked: "<em>31/12</em>/2009"');
$t->is( $specimens[4]->getAcquisitionDate() , array('year'=>2009, 'month'=>'', 'day'=>'', 'hour'=>'', 'minute'=>'', 'second'=>''), 'Correct date masked as array');