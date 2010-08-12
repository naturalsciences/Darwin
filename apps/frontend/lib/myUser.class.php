<?php

class myUser extends sfBasicSecurityUser
{
  public function getId()
  {
    return $this->getAttribute('db_user_id');
  }

  public function getDbUserType()
  {
    return $this->getAttribute('db_user_type');
  }
  
  /**
   * Save the visible columns in the search
   * @param array $columns Array of columns names
   */
  public function storeVisibleCols($columns)
  {
    $this->setAttribute('spec_search_columns',$columns);
  }

  /**
   * Fetch the visible columns in the search
   * @return array an array of visible fields
   */
  public function fetchVisibleCols()
  {
    return $this->getAttribute('spec_search_columns',array());
  }


  public function storeRecPerPage($number)
  {
    $this->setAttribute('rec_per_page',$number);
  }
  
  public function fetchRecPerPage()
  {
    return $this->getAttribute('rec_per_page',10);
  }
}
