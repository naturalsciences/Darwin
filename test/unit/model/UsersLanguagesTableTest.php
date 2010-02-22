<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
 
$t = new lime_test(3, new lime_output_color());
$t->info('Get languages for User "root"');
$userId = Doctrine::getTable('Users')->findOneByFamilyName('Evil')->getId();
$userLanguages = Doctrine::getTable('UsersLanguages')->findByUsersRef($userId);
$t->is($userLanguages[0]->getPreferredLanguage(), true, 'Language "'.$userLanguages[0]->getLanguageCountry().'" is well the "preferred" language');
$t->is(Doctrine::getTable('UsersLanguages')->getPreferredLanguage($userId)->getLanguageCountry(), 'en', '"en" is well the "preferred" language (got by getPreferredLanguage($userId) method)');
$t->info('Reset now preferred languages');
Doctrine::getTable('UsersLanguages')->removeOldPreferredLang($userId);
$userLanguages = Doctrine::getTable('UsersLanguages')->findByUsersRef($userId);
$t->is($userLanguages[0]->getPreferredLanguage(), false, 'Language "'.$userLanguages[0]->getLanguageCountry().'" is now "preferred" language "no more"');

