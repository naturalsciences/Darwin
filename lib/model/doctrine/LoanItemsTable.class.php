<?php

class LoanItemsTable extends DarwinTable
{
  public static function getInstance()
  {
    return Doctrine_Core::getTable('LoanItems');
  }

  public function findForLoan($id)
  {
    $q = Doctrine_Query::create()
      ->From('LoanItems i')
      ->andwhere('i.loan_ref = ?', $id)
      ->orderBy('i.id');
    return $q->execute();
  }

}