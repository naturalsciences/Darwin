<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class UsersLanguagesTable extends DarwinTable
{
    /**
    * Get The preferred Language of a user
    * @param int $user_id the id of the user
    * @return a UsersLanguages Record or null if not found
    */
    public function getPreferredLanguage($user_id)
    {
        $q = Doctrine_Query::create()
            ->from('UsersLanguages ul')
            ->addWhere('ul.preferred_language = true')
            ->addWhere('ul.users_ref = ?', $user_id);
        return $q->fetchOne();
    }
    
    public function getLangByUser($user_id)
    {
        $q = Doctrine_Query::create()
            ->from('UsersLanguages ul')
            ->addWhere('ul.users_ref = ?', $user_id)
            ->orderBy('ul.preferred_language DESC, ul.language_country ASC');
        return $q->execute(); 
    }
    
    public function removeOldPreferredLang($user_id)
    {
	$q = Doctrine_Query::create()
            ->update('UsersLanguages')
            ->set('preferred_language','?',false)
            ->addWhere('users_ref = ?', $user_id);
      return $q->execute();
    }
}
