<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class MyPreferencesTable extends DarwinTable
{
  
  public function getWidgetTitle($userId, $widget, $category)
  {
    $q = Doctrine_Query::create()
         ->select('p.title_perso as title')
         ->from('MyPreferences p')
         ->andWhere('p.user_ref = ?', $userId)
         ->andWhere('p.group_name = ?', $widget)
         ->andWhere('p.category = ?', $category)
         ->andWhere('p.is_available = true') ;
    return $q->execute();
  }

  public function getWidgets($category)
  {
      $q = Doctrine_Query::create()
            ->from('MyPreferences p INDEXBY p.group_name')
            ->orderBy('p.col_num ASC, p.order_by ASC, p.group_name ASC');
    return $this->addCategoryUser($q,$category)->execute();
  }
  
  public function setUserRef($ref)
  {
    $this->user_ref = $ref;
    return $this;
  }

    
  public function changeWidgetStatus($category, $widget, $status)
  {


    $q = Doctrine_Query::create()
	->update('MyPreferences p');
	if($status == "open" || $status == "close")
    {
        $q->set('p.opened', $status=="open" ? 'true' : 'false' );
    }
    elseif($status == "visible")
    {
        $q->set('p.visible', 'true');
        $q->set('p.opened', 'true');
        $q->set('p.col_num', 1);
        
        $q2 = Doctrine_Query::create()
	    ->select('MAX(p.order_by) as ord')
            ->from('MyPreferences p')
	    ->andWhere('p.visible=?','true');

	$this->addCategoryUser($q2,$category);
	$result = $q2->execute()->getFirst();
	$q->set('p.order_by', (isset($result['ord'])) ? $result['ord']+1 : 1);
    }
    elseif($status == "hidden")
    {
        $q->set('p.visible', 'false');
    }

    $this->addCategoryUser($q,$category)
        ->andWhere('p.group_name = ?',$widget);
    return $q->execute();
  }

  public function changeOrder($category, $col1, $col2)
  {
    $this->updateWidgetsOrder($col1, 1, $category);
    $this->updateWidgetsOrder($col2, 2, $category);
  }
  
  public function setWidgets($right,$val)
  {
	$file = MyPreferences::getFileByRight($right) ;
	if($file)
	{
		$data = new Doctrine_Parser_Yml();
		$array = $data->loadData($file);
		foreach ($array as $widget => $array_values) {
		   if($array_values['mandatory']) continue ; //mandatory widget have already at true, and we don't want it could be changed
		   $q = Doctrine_Query::create()
             ->update('MyPreferences p') 
             ->set('p.is_available',($val==1?"true":"false")) 
		   ->where('p.group_name = ?',$array_values['group_name'])
		   ->andWhere('p.category = ?', $array_values['category'])
		   ->execute() ;
		}
	}  	
  }

  public function addCategoryUser(Doctrine_Query $q = null, $category)
  {
    if (sfConfig::get('sf_logging_enabled') && !$this->user_ref)
    {
     sfContext::getInstance()->getLogger()->warning("No User defined with setUserRef");
	throw new Exception('No User defined for query');
    }
    if (is_null($q))
    {
        $q = Doctrine_Query::create()
            ->from('MyPreferences p');
    }

    $alias = $q->getRootAlias();

    $q->andWhere($alias . '.user_ref = ?', $this->user_ref)
        ->andWhere($alias . '.category = ?', $category)
        ->andWhere('p.is_available = true') ;
    return $q;
  }
 
   public function getWidgetsList($level)
  {
  	$q = Doctrine_Query::create()
            ->from('MyPreferences p')
  		  ->where('p.user_ref = ?', $this->user_ref) ;
     if ($level < 4) $q->andWhere('p.is_available = true') ;
     $q->orderBy('category,group_name');
   	return $q->execute() ;	
  }
 
  public function getAvailableWidgets()
  {
  	$q = Doctrine_Query::create()
            ->from('MyPreferences p')
            ->where('p.is_available = true') 
  		  ->Andwhere('p.user_ref = ?', $this->user_ref) ;
   	return $q->execute() ;	
  }

  public function updateWidgetsOrder($widget_array, $col_num, $category)
  {
    if(! is_array($widget_array))
        throw new Exception ('Widgets must be an array');
    $q = Doctrine_Query::create()
	->update('MyPreferences p')
	->set('p.col_num','?',$col_num)
	->set('p.order_by',"fct_array_find(?,group_name::text) ",implode(",",$widget_array))
	->andWhereIn('p.group_name',$widget_array);
	
    $this->addCategoryUser($q,$category)->execute();
  }

}
