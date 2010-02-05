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

  /**
  * Find Only institution not people
  * @param int the id of the people
  * @return Doctrine_Record 
  */
  public function findInstitution($id)
  {
    $q = Doctrine_Query::create()
	 ->from('Institutions p')
	 ->where('p.id = ?', $id)
	 ->andWhere('p.is_physical = ?', false);

    return $q->fetchOne(); 
  }
}
