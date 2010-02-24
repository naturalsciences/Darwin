<?php 
include(dirname(__FILE__).'/../../bootstrap/Doctrine.php');
 
$t = new lime_test(4, new lime_output_color());
$t->info('Get languages for User "root"');
$userId = Doctrine::getTable('Users')->findOneByFamilyName('Evil')->getId();
$userLanguages = Doctrine::getTable('UsersLanguages')->findByUsersRef($userId);
$lang = $userLanguages[0];
if($userLanguages[0]->getLanguageCountry() != "en")
{
  $lang = $userLanguages[1];
}

$t->is($lang->getPreferredLanguage(), true, 'Language "'.$lang->getLanguageCountry().'" is well the "preferred" language');
$t->is(Doctrine::getTable('UsersLanguages')->getPreferredLanguage($userId)->getLanguageCountry(), 'en', '"en" is well the "preferred" language (got by getPreferredLanguage($userId) method)');
$t->info('Reset now preferred languages');
Doctrine::getTable('UsersLanguages')->removeOldPreferredLang($userId);
$userLanguages = Doctrine::getTable('UsersLanguages')->findByUsersRef($userId);
foreach($userLanguages as $lang)
  $t->is($lang->getPreferredLanguage(), false, 'Language "'.$lang->getLanguageCountry().'" is now "preferred" language "no more"');

