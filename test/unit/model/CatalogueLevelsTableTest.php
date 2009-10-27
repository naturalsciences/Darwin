<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(2, new lime_output_color());

$t->info('getLevelsForTaxo');
$levels = Doctrine::getTable('CatalogueLevels')->getLevelsForTaxo();
$t->is($levels->count(),54,'There are some results in taxo');
$t->is($levels[1]->getLevelName(),'kingdom','The first is kingdom');