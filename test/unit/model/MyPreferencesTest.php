<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(16, new lime_output_color());

$userEvil = Doctrine::getTable('Users')->findOneByFamilyName('Evil')->getId();

$t->comment('->getWidgets()');
$t->is(count(Doctrine::getTable('MyPreferences')
        ->setUserRef($userEvil)
        ->getWidgets('board_widget')),4,'Get all board widget');
$t->is(count(Doctrine::getTable('MyPreferences')
        ->setUserRef($userEvil)
        ->getWidgets('specimen_widget')),2,'Get all board widget');

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


$t->comment('->updateWidgetsOrder()');

try {
Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->updateWidgetsOrder("savedSpecimens,savedSearch", 2, "board_widget");
    $t->fail('updateWidgetsOrder must throw a exception for bad arguments');
}
catch(Exception $e)
{
    $t->pass('exception for bad arguments well throws');
}


Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->updateWidgetsOrder(array("savedSpecimens","savedSearch"), 2, "board_widget");

$widgets = Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->createQuery()
    ->andWhereIn("group_name",array("savedSpecimens","savedSearch"))
    ->orderBy('group_name ASC')
    ->execute();

$t->is($widgets[1]->getOrderBy(),1, "Is order set correctly");
$t->is($widgets[0]->getOrderBy(),2, "Is order set for everyone");
$t->is($widgets[0]->getColNum(),2, "Is col_num set correctly");

$t->comment('->changeOrder()');
Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->changeOrder('board_widget',array("savedSpecimens"), array("savedSearch"));

$widgets = Doctrine::getTable('MyPreferences')
    ->setUserRef($userEvil)
    ->createQuery()
    ->andWhereIn("group_name",array("savedSpecimens","savedSearch"))
    ->orderBy('group_name ASC')
    ->execute();

$t->is($widgets[0]->getOrderBy(),1, "Is order set correctly");
$t->is($widgets[1]->getOrderBy(),1, "Is order set for everyone");
$t->is($widgets[0]->getColNum(),2, "Is col_num set correctly");
$t->is($widgets[1]->getColNum(),1, "Is col_num for everyone");