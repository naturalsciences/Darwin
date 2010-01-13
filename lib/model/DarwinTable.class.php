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

  public function addCatalogueReferences($query, $table_name, $record_id, $alias)
  {
    $query->andWhere($alias.'.referenced_relation = ?',$table_name)
         ->andWhere($alias.'.record_id = ?',$record_id)
	 ->andWhere($alias.'.record_id != 0');
    return $query;
  }

}