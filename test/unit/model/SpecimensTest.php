<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(7, new lime_output_color());

$toDate = new FuzzyDateTime('2009/12/31', 32);

$collection =  Doctrine_Query::create()
	      ->from('Collections c')
	      ->where('c.name = ?', 'Vertebrates')
	      ->orderBy('c.name ASC')
	      ->limit(1)
	      ->fetchOne();

$specimens =  Doctrine_Query::create()
	      ->from('Specimens s')
	      ->orderBy('s.collection_ref ASC, s.taxon_ref')
	      ->count();
$t->is( $specimens , 4, 'The number of spec is right');

$specimen = new Specimens();
$specimen->setId(1000) ;
$specimen->setCollectionRef($collection['id']);
$specimen->setAcquisitionDate($toDate);
$specimen->save();

$specimens =  Doctrine_Query::create()
	      ->from('Specimens s')
	      ->orderBy('s.collection_ref ASC, s.taxon_ref')
	      ->execute();
$t->is( $specimens->count() , 5, 'New "Vertebrate" specimen inserted');

$t->is( $specimens[1]->getAcquisitionDateMasked() , '<em>31/12</em>/2009', 'Correct date masked: "<em>31/12</em>/2009"');
$t->is( $specimens[1]->getAcquisitionDate() , array('year'=>2009, 'month'=>'', 'day'=>'', 'hour'=>'', 'minute'=>'', 'second'=>''), 'Correct date masked as array');
$t->is( $specimens[1]->getName(), '[VERT. 12457]', 'This specimen has the formated name: "[VERT. 12457]"');
$t->is( $specimens[4]->Taxonomy->getNameWithFormat(), 'Falco Peregrinus Tunstall, 1771', 'The previous specimen has the formated name: "Falco Peregrinus Tunstall, 1771"');
$specimens[4]->Taxonomy->setExtinct(true);
$specimens[4]->Taxonomy->save();
$t->is( $specimens[4]->Taxonomy->getNameWithFormat(), 'Falco Peregrinus Tunstall, 1771 †', 'Now this specimen with a taxon definition set at extinct has the formated name: "Falco Peregrinus Tunstall, 1771 †"');
