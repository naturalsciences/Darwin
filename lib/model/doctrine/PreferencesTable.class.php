<?php


class PreferencesTable extends Doctrine_Table
{
  public static function getInstance()
  {
    return Doctrine_Core::getTable('Preferences');
  }

  public function getPreference($user_id, $key, $default="")
  {
    $result = Doctrine_Query::create()
      ->from('Preferences p')
      ->andwhere('p.user_ref = ?', $user_id)
      ->andWhere('p.pref_key = ?',$key)
      ->fetchOne();
    if($result)
      return $result->getPrefValue();
    else
      return $default;
  }
}