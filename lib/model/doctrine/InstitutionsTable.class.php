<?php

class InstitutionsTable extends Doctrine_Table
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
      $results = Doctrine_Query::create()->
	select('DISTINCT(sub_type) as type')->
	from('Institutions i')->
	execute();
      return $results;
    }
}
