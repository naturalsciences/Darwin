<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(5, new lime_output_color());

$tools = Doctrine::getTable('CollectingTools')->fetchTools();
$t->is(count($tools), 2, '"2" Tools defined by default');
$t->info('Insert a new value');
$newVal = Doctrine::getTable('CollectingTools')->addTool('Crochet');
$tools = Doctrine::getTable('CollectingTools')->fetchTools();
$t->is(count($tools), 3, '"3" Tools defined now');
$t->is($tools[$newVal], 'Crochet', 'The new value inserted is well "Crochet"');
$increment = 1;
foreach($tools as $value)
{
  if($increment==2)
  {
    $t->is($value, 'Crochet', 'The new value ("Crochet") is well the "second" one to be in the list brought by "fetchTools"');
    break;
  }
  $increment++;
}
$t->info('Try to insert a duplicate value and test it fails');
$newVal = Doctrine::getTable('CollectingTools')->addTool('Crochet');
$t->like($newVal, '/duplicate key value violates unique constraint/', 'Duplicate error');