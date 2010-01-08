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

}