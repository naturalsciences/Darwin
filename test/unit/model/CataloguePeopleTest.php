<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(3, new lime_output_color());

$t->info('getAuthorTypes test');
$catPeo = new CataloguePeople;
$types = $catPeo->getAuthorTypes();
$t->is(count($types), 6, 'There are 6 differents types');
$t->is($types[0], '', 'First type is well "Main Author"');
$t->is($types[5], '', 'Last type is well "Related"');