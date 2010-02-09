<?php

class InstitutionsTable extends DarwinTable
{
  /**
  * Find all distinct tyoe of institutions
  * @return Doctrine_Collection with only the key 'type'
  */
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
