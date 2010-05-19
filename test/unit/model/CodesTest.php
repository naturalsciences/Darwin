<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(6, new lime_output_color());

$taxon = Doctrine::getTable('Taxonomy')->findOneByName('Falco Peregrinus');
$specimen = Doctrine::getTable('Specimens')->findOneByTaxonRef($taxon->getId());
$code = new Codes;
$code->setReferencedRelation('specimens');
$code->setRecordId($specimen->getId());
$code->setCodeCategory('Temporary');
$code->setCodePrefix('VERT.');
$code->setCodePrefixSeparator('/');
$code->setCodeSuffix('AFTER');
$code->setCodeSuffixSeparator('/');
$code->save();

$codes = Doctrine::getTable('Codes')->getCodesRelated('specimens', $specimen->getId());

$t->is(count($codes), 5, '"5" codes available for specimen "'.$specimen->getId().'"');
$t->is($codes[0]->getCodeFormated(), '1', 'The Code is well "1"');
$t->is($codes[1]->getCodeFormated(), 'VERT. 12456', 'The Code is well "VERT. 12456"');
$t->is($codes[2]->getCodeFormated(), 'VERT./1548548 Abou', 'The Code is well "VERT./1548548 Abou"');
$t->is($codes[3]->getCodeFormated(), 'VERT./85486846', 'The Code is well "VERT./85486846"');
$t->is($codes[4]->getCodeFormated(), 'VERT./-/AFTER', 'The Code is well "VERT./-/AFTER"');