<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class UsersAddresses extends BaseUsersAddresses
{
  public static $possible_tags = array('home'=>'Home', 'work'=>'Work', 'pref'=>'Preferred', 'intl'=>'International', 'postal'=>'Postal');

  /**
  * Get tags of the address as an array (only label not keys)
  * @return array Array of tags for this address
  */
  public function getTagsAsArray()
  {
    $array = explode(',',$this->_get('tag'));
    $result = array();

    foreach($array as $tag)
    {
      $tag=trim($tag);
      if(isset(self::$possible_tags[$tag]))
    	$result[] = self::$possible_tags[$tag];
    }
    try{
        $i18n_object = sfContext::getInstance()->getI18n();
    }
    catch( Exception $e )
    {
        return $result;
    }
    return array_map(array($i18n_object, '__'), $result);       
  }


  public static function getPossibleTags()
  {
    try{
        $i18n_object = sfContext::getInstance()->getI18n();
    }
    catch( Exception $e )
    {
        return self::$possible_tags;
    }
    return array_map(array($i18n_object, '__'), self::$possible_tags);    
  }
}
