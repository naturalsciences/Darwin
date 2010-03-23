<?php
class DarwinTable extends Doctrine_Table
{

    public static function getFilterForTable($table)
    {
      return self::getModelForTable($table). 'FormFilter';
    }
    
    public static function getFormForTable($table)
    {
      return self::getModelForTable($table). 'Form';
    }

    public static function getModelForTable($table)
    {
      return sfInflector::camelize($table);
    }

   /**
     * Finds a record by its identifier if the id is greater than 0
     *
     * @param integer $rowId          Database Row ID
     * @return Doctrine_Record or false if no result
     */
    public function findExcept($rowId)
    {
        if ($rowId != 0)
        { 
          return parent::find($rowId);
        }
        else
        {
          return false;
        }
    }

  protected function getI18N()
  {
     return sfContext::getInstance()->getI18N();
  }

  /**
   * addCatalogueReferences
   * Filter by Referenced table and record Id
   * @param Doctrine_Query $query the query
   * @param string $table_name the table name to filter results
   * @param int $record_id record id of the referenced record
   * @param string $alias alias used for table in the query (myTable t ==> alias is 't')
   * @param boolean $with_zero permit to include the referenced record 0
   * @return Doctrine_Query the modified query
  */
  public function addCatalogueReferences($query, $table_name, $record_id, $alias, $with_zero = false)
  {
    $query->andWhere($alias.'.referenced_relation = ?',$table_name)
         ->andWhere($alias.'.record_id = ?',$record_id);
    if(! $with_zero)
	 $query->andWhere($alias.'.record_id != 0');
    return $query;
  }
  /**
   * createDistinct 
   * Initiate a distinct query on a given model and column
   * @param string $model The model name
   * @param string $column The db column name that will be distinct
   * @param string $new_col The name of the new column with distincts
   * @param string $table_alias the alias of the used table
   * @return Doctrine_Query with the distinct ordered by the $new_col ASC.
  */
  public function createDistinct($model, $column, $new_col='item', $table_alias = 't')
  {
    $q = Doctrine_Query::create()->
	select("DISTINCT($table_alias.$column) as $new_col")->
	from("$model $table_alias")->
	orderBy("$new_col ASC");
    return $q;
  }

  /**
   * findWithParents
   * Find records with his parents order by the path ( root first)
   * @param $id int Id of the record to search
   * @return Doctrine_Collection A collection of records
  */
  public function findWithParents($id)
  {
    $self_unit = Doctrine::getTable($this->getTableName())->find($id);
    $ids = explode('/', $self_unit->getPath().$self_unit->getId());

    array_shift($ids); //Removing the first blank element 

    $q = Doctrine_Query::create()
	 ->from($this->getTableName())
	 ->whereIn('id', $ids)
	 ->orderBy('path ASC');
    return $q->execute();
  }

}
