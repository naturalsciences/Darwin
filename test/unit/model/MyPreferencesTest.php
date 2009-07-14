<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');

$t = new lime_test(1, new lime_output_color());
$t->comment('->getBoardWidgets()');
$t->is(count(Doctrine::getTable('MyPreferences')
        ->setUserRef(Doctrine::getTable('Users')->findOneByFamilyName('Evil')->getId())
        ->getBoardWidgets()),4,'Get all board widget');

$t->comment('->getBoardWidgets()');
$t->is(count(Doctrine::getTable('MyPreferences')
        ->setUserRef(Doctrine::getTable('Users')->findOneByFamilyName('Evil')->getId())
        ->getBoardWidgets()),4,'Get all board widget');