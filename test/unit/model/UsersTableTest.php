<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
 
$t = new lime_test(3, new lime_output_color());
$t->comment('->getUserByPassword()');
$t->is(Doctrine::getTable('Users')->getUserByPassword("root","brol"),false,'Test the local login with a wrong password');
$t->is(Doctrine::getTable('Users')->getUserByPassword("root","evil")->getFamilyName(),"Evil",'Test the local login with a good password');
$t->is(''.Doctrine::getTable('Users')->getUserByPassword("root","evil"),"Evil Root (Mr)",'Test the local login with a good password');
