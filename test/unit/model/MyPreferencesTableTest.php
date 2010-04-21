<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
$t = new lime_test(24, new lime_output_color());

$userEvil = Doctrine::getTable('Users')->findOneByFamilyName('Evil')->getId();

try
{
  Doctrine::getTable('MyPreferences')->getWidgets('board_widget');
  $t->fail('Must throw an exception for not giving user id');
}
catch (Exception $e)
{
  $t->pass('exception caught successfully');
}

$t->info('->getWidgets()');
$t->isnt(Doctrine::getTable('MyPreferences')->setUserRef($userEvil)->addCategoryUser(null,'board_widget'), null,'It\'s not null');

$t->is(count(Doctrine::getTable('MyPreferences')
        ->setUserRef($userEvil)
        ->getWidgets('board_widget')),4,'Get all board widget');
$t->is(count(Doctrine::getTable('MyPreferences')
        ->setUserRef($userEvil)
        ->getWidgets('specimen_widget')),15,'Get all specimen widget');

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

$title = Doctrine::getTable('MyPreferences')->getWidgetTitle($userEvil, $widgets[0]->getGroupName(), $widgets[0]->getCategory());
$title = $title->toArray();

$t->is($widgets[0]->getTitlePerso(), $title[0]['title'], 'Title perso is well what is coming from "getWidgetTitle" method');

$q = Doctrine_Query::create()
    ->delete('MyPreferences p')
    ->Where('p.user_ref = ?', $userEvil)
    ->execute();
    
$t->is(count(Doctrine::getTable('MyPreferences')
        ->setUserRef($userEvil)
        ->getWidgets('board_widget')),0,'All widget would have been deleted');

$t->comment('->addWidgets()');        
$widget = new Users() ;
$widget->addUserWidgets($userEvil);

$q = Doctrine_Query::create()
    ->from('MyPreferences p')
    ->Where('p.user_ref = ?', $userEvil)
    ->execute();
$t->is($q->count(),72,'Now Root has his 72 widgets') ; 

Doctrine::getTable('Mypreferences')->setUserRef($userEvil)->setWidgets('Registered user',true) ;

$t->comment('->setWidgets()');  
$t->is(count(Doctrine::getTable('MyPreferences')
        ->setUserRef($userEvil)
        ->getWidgets('board_widget')),2,'4 board widgets but only 2 visible for a registered user');

Doctrine::getTable('Mypreferences')->setUserRef($userEvil)->setWidgets('Registered user',false) ;

$t->is(count(Doctrine::getTable('MyPreferences')
        ->setUserRef($userEvil)
        ->getWidgets('board_widget')),0,'Removing \'Registered user\' right : 0 board widgets visible now');     

$t->comment('->getWidgetsList()');
$t->is(count(Doctrine::getTable('MyPreferences')
		->getWidgetsList(2)),1,'Get a list of all available widgets (0 like above), but 1 mandatory !') ;
