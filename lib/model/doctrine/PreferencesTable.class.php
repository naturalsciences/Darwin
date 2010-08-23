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
  
  public function setPreference($user_id, $key, $value)
  {
    $result = Doctrine_Query::create()
      ->from('Preferences p')
      ->andwhere('p.user_ref = ?', $user_id)
      ->andWhere('p.pref_key = ?',$key)
      ->fetchOne();

    if(!$result)
    {
      $result = new Preferences();
      $result->fromArray(array(
        'user_ref' => $user_id,
	'pref_key' => $key,
    }

    $result->setPrefValue($value);
    $result->save();
    return true;
  }
  public function getAllPreferences($user_id, $asked_keys = null)
  {
    $q = Doctrine_Query::create()
      ->from('Preferences p')
      ->andwhere('p.user_ref = ?', $user_id)
      ->andWhereIn('p.pref_key',$asked_keys);
    $query_results = $q->execute();
    $results = array();
    foreach($query_results as $row)
    {
      $key = $row->getPrefKey();
      $results[$key] = $row->getPrefValue();
    }

    foreach($asked_keys as $missing)
    {
      if(! isset($results[$missing]))
        $results[$missing] = '';
    }
    return $results;
  }

  public function saveAllPreferences($user_id, $keys)
  {
    foreach($keys as  $key => $value)
    {
      $this->setPreference($user_id, $key, $value);
    }
  }
}

