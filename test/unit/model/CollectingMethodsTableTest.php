<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(5, new lime_output_color());

$methods = Doctrine::getTable('CollectingMethods')->fetchMethods();
$t->is(count($methods), 2, '"2" Methods defined by default');
$t->info('Insert a new value');
$newVal = Doctrine::getTable('CollectingMethods')->addMethod('Intuition');
$methods = Doctrine::getTable('CollectingMethods')->fetchMethods();
$t->is(count($methods), 3, '"3" Methods defined now');
$t->is($methods[$newVal], 'Intuition', 'The new value inserted is well "Intuition"');
foreach($methods as $value)
{
  $t->is($value, 'Intuition', 'The new value ("Intuition") is well the "first" one to be in the list brought by "fetchMethods"');
  break;
}
$t->info('Try to insert a duplicate value and test it fails');
$newVal = Doctrine::getTable('CollectingMethods')->addMethod('Intuition');
$t->like($newVal, '/duplicate key value violates unique constraint/', 'Duplicate error');