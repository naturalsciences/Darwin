<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(6, new lime_output_color());

$toDate = new FuzzyDateTime('2009/12/31', 32);

$collection = Doctrine::getTable('Collections')->findOneByName('Vertebrates');
$specimen = new Specimens();
$specimen->setId(1000) ;
$specimen->setCollectionRef($collection['id']);
$specimen->setAcquisitionDate($toDate);
$specimen->save();

$specimens = Doctrine::getTable('Specimens')->findAll();
$t->is( $specimens->count() , 5, 'New "Vertebrate" specimen inserted');
$t->is( $specimens[4]->getAcquisitionDateMasked() , '<em>31/12</em>/2009', 'Correct date masked: "<em>31/12</em>/2009"');
$t->is( $specimens[4]->getAcquisitionDate() , array('year'=>2009, 'month'=>'', 'day'=>'', 'hour'=>'', 'minute'=>'', 'second'=>''), 'Correct date masked as array');
$t->is( $specimens[4]->getName(), '[VERT. 12457]', 'This specimen has the formated name: "[VERT. 12457]"');
$t->is( $specimens[2]->Taxonomy->getNameWithFormat(), 'Falco Peregrinus Tunstall, 1771', 'The previous specimen has the formated name: "Falco Peregrinus Tunstall, 1771"');
$specimens[2]->Taxonomy->setExtinct(true);
$specimens[2]->Taxonomy->save();
$t->is( $specimens[2]->Taxonomy->getNameWithFormat(), 'Falco Peregrinus Tunstall, 1771 †', 'Now this specimen with a taxon definition set at extinct has the formated name: "Falco Peregrinus Tunstall, 1771 †"');
