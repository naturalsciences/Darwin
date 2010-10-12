<?php


class CollectionsRegUserTable extends Doctrine_Table
{
    
  public static function getInstance()
  {
      return Doctrine_Core::getTable('CollectionsRegUser');
  }
  
  public function getCollectionRegUser($collection_ref) 
  {
     $q = Doctrine_Query::create()
	    ->from('CollectionsRegUser cr')
	    ->innerJoin('cr.Users u')
	    ->andWhere('cr.collection_ref = ?',$collection_ref) ; 
    return $q->execute() ;  
  }    
}

