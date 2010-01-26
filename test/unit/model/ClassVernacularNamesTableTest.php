<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(5, new lime_output_color());

$lastIg = Doctrine::getTable('taxonomy')->find('4');

$vernacular_names = new ClassVernacularNames;

$vernacular_names->setCommunity('Français');
$vernacular_names->setReferencedRelation('taxonomy');
$vernacular_names->setRecordId($lastIg->getId());
$vernacular_names->VernacularNames[]->name ='Faux con';
$vernacular_names->save();

$vernacular_names = new ClassVernacularNames;

$vernacular_names->setCommunity('Wallon');
$vernacular_names->setReferencedRelation('taxonomy');
$vernacular_names->setRecordId($lastIg->getId());
$vernacular_names->save();

$vernacular_names = Doctrine::getTable('ClassVernacularNames')->findForTable('taxonomy',4);
$t->is( count($vernacular_names) , 2, 'There is vernacular names for this table / record_id');
$t->is( $vernacular_names[0]->getCommunity() , 'Français', 'Community well inserted');
$t->is( count($vernacular_names[0]->VernacularNames) , 1, 'There is also 1 name associated');

$communities = Doctrine::getTable('ClassVernacularNames')->getDistinctCommunities();
$t->is( count($communities) , 2, 'There are 2 different communities');
$t->is( $communities[1]->getCommunity() , 'Wallon', 'and are accessible through getCommunity');
