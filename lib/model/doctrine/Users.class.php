<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Users extends BaseUsers
{
  const REGISTERED_USER = 1;
  const ENCODER = 2;
  const MANAGER = 4;
  const ADMIN = 8;

  public function __toString()
  {
    return $this->getFormatedName();
  }
    
  /**
	* function getStatus
	* @return string a 'title' for a user : 'M' for a Male, 'F' for a female, and 'moral' for a non physical user
	*/
  public function getStatus()
  {
    if (!$this->getIsPhysical())
    	return ("moral") ;
    else
    {
	    $gender = $this->getGender();
	    return (strtolower( $gender=='' ? 'M' : $gender));
	  }
  }
    
  public static function getTypes($options)
  {
    if (isset($options['screen']) && $options['screen'] == 1)
      return array( self::REGISTERED_USER => Users::getTypeName(self::REGISTERED_USER) );

    $db_user_type = array(
    self::REGISTERED_USER => Users::getTypeName(self::REGISTERED_USER),
    self::ENCODER => Users::getTypeName(self::ENCODER),
    self::MANAGER => Users::getTypeName(self::MANAGER),
    self::ADMIN => Users::getTypeName(self::ADMIN)
      );

    if (isset($options['screen']) && $options['screen'] == 3)
    {
      array_shift($db_user_type) ;
      return $db_user_type ;
    }	
    if (isset($options['screen']) && $options['screen'] == 2)    
	    array_pop($db_user_type);
    if ($options['db_user_type'] != self::ADMIN)
    { 
	    array_pop($db_user_type);
	    array_pop($db_user_type);
	  }
	  return $db_user_type ;
  }
    
  public static function getTypeName($db_user_type)
  {
	  switch ($db_user_type)
	  {
		  case self::REGISTERED_USER : return 'Registered user';
		  case self::ENCODER : return 'Encoder';
		  case self::MANAGER : return 'Collection manager';
		  case self::ADMIN : return 'Administrator';   	  
	  }
  }

    /**
	* function to add all user's widgets in my_widgets table
	* use user id and db_user_type
	* @return the number of widget added
	*/
  public function addUserWidgets()
  {
	  $count_widget = 0;
	  $array_right = Users::getTypes(array('db_user_type' => self::ADMIN)) ;
	  foreach ($array_right as $key => $right)
	  {
		  $file = MyWidgets::getFileByRight($key) ;
		  if($file)
		  {
			  $data = new Doctrine_Parser_Yml();
			  $array = $data->loadData($file);
			  foreach ($array as $widget => $array_values)
			  {
				$pref = new MyWidgets() ;
				$array_values['user_ref'] = $this->getId();
				$pref->fromArray($array_values);
			  $pref->setIsAvailable(true);

				$pref->save();
				$count_widget++;
			  }
		  }
	  }
	  return $count_widget;
  }
}
