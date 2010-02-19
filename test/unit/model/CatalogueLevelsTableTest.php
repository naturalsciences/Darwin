<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(10, new lime_output_color());

$t->info('getLevelsForTaxonomy');
$levels = Doctrine::getTable('CatalogueLevels')->getLevelsForTaxonomy();
$t->is($levels->count(),54,'There are some results in taxo');
$t->is($levels[1]->getLevelName(),'kingdom','The first is kingdom');
$t->info('getLevelsForChronostratigraphy');
$levels = Doctrine::getTable('CatalogueLevels')->getLevelsForChronostratigraphy();
$t->is($levels->count(),9,'9 levels in chronostratigraphy');
$t->is($levels[1]->getLevelName(),'era','The first is era');
$t->info('getLevelsForLithology');
$levels = Doctrine::getTable('CatalogueLevels')->getLevelsForLithology();
$t->is($levels->count(),4,'4 levels in lithology');
$t->is($levels[1]->getLevelName(),'group','The first is group');
$t->info('getLevelsForLithostratigraphy');
$levels = Doctrine::getTable('CatalogueLevels')->getLevelsForLithostratigraphy();
$t->is($levels->count(),6,'6 levels in lithostratigraphy');
$t->is($levels[1]->getLevelName(),'formation','The first is formation');
$t->info('getLevelsForMineralogy');
$levels = Doctrine::getTable('CatalogueLevels')->getLevelsForMineralogy();
$t->is($levels->count(),5,'5 levels in mineralogy');
$t->is($levels[1]->getLevelName(),'division','The first is division');