<?php

/**
 * LoanRightsTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class LoanRightsTable extends Doctrine_Table
{
  /**
   * Returns an instance of this class.
   *
   * @return object LoanRightsTable
   */

  public static function getInstance()
  {
    return Doctrine_Core::getTable('LoanRights');
  }

   /**
   *  getEncodingRightsForUser
   *
   *  Returns an array of loans and whether the user has 
   *  encoding rights (according to the LoanRights table)
   *  
   *  @param $user the user id for whom you want to get the array of encoding rights.
   *  
   *  @return an array of rights for the given user
   *            key: id of the loan
   *            value: boolean of whether he has the encoding rights for the given loan.
   */

  public function getEncodingRightsForUser( $user )
  {
    $q = Doctrine_Query::create()
      ->select('has_encoding_right, loan_ref')
      ->from('LoanRights')
      ->where('user_ref = ?', $user);
    $result = $q->execute();  

    $rights = array();
    foreach( $result as $res )
      $rights[$res->getLoanRef()] = $res->getHasEncodingRight();

    return $rights;
  }

}