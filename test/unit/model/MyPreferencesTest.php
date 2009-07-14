<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(7, new lime_output_color());

$userEvil = Doctrine::getTable('Users')->findOneByFamilyName('Evil')->getId();

$t->comment('->getBoardWidgets()');
$t->is(count(Doctrine::getTable('MyPreferences')
        ->setUserRef($userEvil)
        ->getBoardWidgets()),4,'Get all board widget');

$t->comment('->changeWidgetStatus()');

$widget = Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->createQuery()
    ->andWhere("group_name = ?","savedSpecimens")
    ->fetchOne();

$t->is($widget->getOpened(),false, "Status is Open");

Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->changeWidgetStatus("board_widget","savedSpecimens","close");

$widget = Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->createQuery()
    ->andWhere("group_name = ?","savedSpecimens")
    ->fetchOne();

$t->is($widget->getOpened(),false, "Status Open/Close changing");

Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->changeWidgetStatus("board_widget","savedSpecimens","hidden");

$widget = Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->createQuery()
    ->andWhere("group_name = ?","savedSpecimens")
    ->fetchOne();

$t->is($widget->getVisible(),false, "Set status hidden");

Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->changeWidgetStatus("board_widget","savedSpecimens","visible");

$widget = Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->createQuery()
    ->andWhere("group_name = ?","savedSpecimens")
    ->fetchOne();

$t->is($widget->getVisible(),true, "Is status changed to visible");
$t->is($widget->getOpened(),true, "Is status changed to opened");
$t->is($widget->getColNum(),1, "Is colnum changed to 1");