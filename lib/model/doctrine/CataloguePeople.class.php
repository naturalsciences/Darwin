<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CataloguePeople extends BaseCataloguePeople
{
  private static $auth_type = array('Main Author' => 'Main Author',
                     'Secondary Author' => 'Secondary Author',
	                   'Reviewer'=> 'Reviewer',
	                   'Publisher' => 'Publisher', 
	                   'Corrector' => 'Corrector', 
	                   'Related' => 'Related');

  public static function getAuthorTypes()
  {
    try{
        $i18n_object = sfContext::getInstance()->getI18n();
    }
    catch( Exception $e )
    {
        return self::$auth_type;
    }
    return array_map(array($i18n_object, '__'), self::$auth_type);
  }  
 
}
