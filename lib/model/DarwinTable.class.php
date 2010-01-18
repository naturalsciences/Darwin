<?php
class DarwinTable extends Doctrine_Table
{
   /**
     * Finds a record by its identifier except an other one defined.
     *
     * @param integer $rowId          Database Row ID
     * @param integer $excludedRowId  Excluded Row ID
     * @return mixed                  Doctrine_Collection, array, Doctrine_Record or false if no result
     */
    public function findExcept($rowId, $excludedRowId=0)
    {
        if ($rowId != $excludedRowId)
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

}