<?php

class InstitutionsTable extends DarwinTable
{
    public function getAll()
    {
      $q = Doctrine_Query::create()
	 ->from('Institutions i')
	 ->addWhere('i.is_physical = ?',false)
	 ->orderBy('i.formated_name_indexed ASC');
      return $q->execute();
    }

    public function getDistinctSubType()
    {
      return $this->createDistinct('Institutions', 'sub_type', 'type')->execute();
    }
  
}
