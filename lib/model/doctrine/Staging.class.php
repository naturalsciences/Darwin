<?php

/**
 * Staging
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    darwin
 * @subpackage model
 * @author     DB team <darwin-ict@naturalsciences.be>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Staging extends BaseStaging
{
  public $codes = array();

  private static $errors = array(
    'not_found' => 'This %field% was not found in our database, please choose an existing one or remove it',
    'too_much' => 'Too many records match this %field%\'s value, please choose the good one or leave empty',
    'bad_hierarchy'=> 'The hierarchy of this %field% is incorrect, please choose a good one or leave the field empty',
    'people' => 'One or more %field% were not found or have too many results. In both cases, you must choose an existing one or create one.',
    'duplicate' => 'This record seems to have already been saved. You can see it %here% or you can also choose an existing one with the button below.',
  );

  public function getCategory()
  {
      return $this->_get('category');
  }
  public function getGtu()
  {
    return $this->_get('gtu_code');
  }

  public function getTaxon()
  {
    return $this->_get('taxon_name');
  }

  public function getChrono()
  {
    return $this->_get('chrono_name');
  }

  public function getLitho()
  {
    return $this->_get('litho_name');
  }

  public function getMineral()
  {
    return $this->_get('mineral_name');
  }

  public function getLithology()
  {
    return $this->_get('lithology_name');
  }
  public function getInstitution()
  {
    return $this->_get('institution_name');
  }
  public function getIg()
  {
    return $this->_get('ig_num');
  }

  public function getExpedition()
  {
    return $this->_get('expedition_name');
  }

  public function getAcquisition()
  {
    return $this->_get('acquisition_category');
  }

  public function getStationVisible()
  {
    return $this->_get('station_visible');
  }

  public function getStatusFor($field)
  {
    $emtpy = 'fld_empty';
    $tb_completed = 'fld_tocomplete';
    $tb_ok = 'fld_ok';
    if($this[$field] == '')
    {
      return $emtpy;
    }
    elseif($field == "taxon")
    {
      if($this['taxon_ref'] == '')
        return $tb_completed;
      else
        return $tb_ok;
    }
    elseif($field == "chrono")
    {
      if($this['chrono_ref'] == '')
        return $tb_completed;
      else
        return $tb_ok;
    }
    elseif($field == "litho")
    {
      if($this['litho_ref'] == '')
        return $tb_completed;
      else
        return $tb_ok;
    }
    elseif($field == "lithology")
    {
      if($this['lithology_ref'] == '')
        return $tb_completed;
      else
        return $tb_ok;
    }
    elseif($field == "mineral")
    {
      if($this['mineral_ref'] == '')
        return $tb_completed;
      else
        return $tb_ok;
    }
    elseif($field == "institution")
    {
      if($this['institution_ref'] == '')
        return $tb_completed;
      else
        return $tb_ok;
    }
  }
/*
  public function getIdentifier()
  {
    $q = Doctrine_Query::create()
      ->select('i.determination_status') 
      ->from('identifications i')
      ->where('i.record_id = ?',$this->getId())
      ->andWhere('referenced_relation=\'staging\'');
    $identifiers = $q->fetchOne();  
    return $this->getPeopleInError('identifiers',$identifiers) ;
  }
*/
  public function getIndividualCount()
  {
    if($this->_get('individual_count_min') == $this->_get('individual_count_max'))
      return $this->_get('individual_count_min');
    return $this->_get('individual_count_min') .'-'.$this->_get('individual_count_max');
  }

  public function getPartCount()
  {
    if($this->_get('part_count_min') == $this->_get('part_count_max'))
      return $this->_get('part_count_min');
    return $this->_get('part_count_min') .'-'.$this->_get('part_count_max');
  }

  public function getStatus()
  {
    $hstore = new Hstore() ;
    $hstore->import($this->_get('status')) ;
    return $hstore ;
  }

  public function getCodes()
  {
    return $this->codes;
  }

  public function setLinkedInfo($nbr)
  {
    $this->linkedInfo = $nbr;
  }

  public function getLinkedInfo()
  {
    if(isset($this->linkedInfo))
      return $this->linkedInfo - count($this->codes);
    return 0;
  }
  public function setStatus($value)
  {
    $status = '' ;
    foreach($value as $field => $error)
    {
      if($error != 'done') $status .= '"'.$field.'"=>"'.$error.'",' ;
    }
    $this->_set('status', substr($status,0,strlen($status)-1));
  }

  // if tosave is set so it the save of the stagingForm wicht this function, I only return the list a fields in error
  public function getFields($tosave = null)
  {
    $status = $this->getStatus() ;
    if(!$status) return null ;
    $fieldsToShow = array();
    foreach($status as $key => $value)
    {
      if($tosave) 
      {
        // staging_people status are updated by a trigger, so we don't care about it here
        if($value!='people') $fieldsToShow[$key] = $value ;
      }
      else  $fieldsToShow[$key] = array(
                                    'embedded_field' => $this->getFieldsToUseFor($key).'_'.$value, // to TEST
                                    'display_error' => self::$errors[($key=='duplicate'?$key:$value)],
                                    'fields' => $this->getFieldsToUseFor($key));
      if($key == 'duplicate') $fieldsToShow[$key]['duplicate_record'] = $value ;
    }
    return($fieldsToShow) ;
  }

  private function getErrorToDisplay($error_type)
  {
    try{
        $i18n_object = sfContext::getInstance()->getI18n();
    }
    catch( Exception $e )
    {
        return self::$errors[$error_type];
    }
    return array_map(array($i18n_object, '__'), self::$errors[$error_type]);
  }

  private function getFieldsToUseFor($field)
  {
    if($field == 'taxon') return('taxon_ref') ;
    if($field == 'chrono') return('chrono_ref') ;
    if($field == 'litho') return('litho_ref') ;
    if($field == 'mineral') return('mineral_ref') ;
    if($field == 'lithology') return('lithology_ref') ;
    if($field == 'igs') return('ig_ref') ;
    if($field == 'people') return('people') ;
    if($field == 'identifiers') return('identifiers') ;
    if($field == 'institution') return('institution_ref') ;
    //if($field == 'institution_relationship') return('institution_relationship') ;
    if($field == 'duplicate') return('spec_ref') ;
    if($field == 'operator') return('operator') ;
    return($field) ;
  }
}
