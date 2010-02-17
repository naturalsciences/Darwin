<?php

/**
 * Project filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class BaseFormFilterDoctrine extends sfFormFilterDoctrine
{
  public function setup()
  {
  }

  public function addPagerItems()
  {
    $recPerPages = array("1"=>"1", "2"=>"2", "5"=>"5", "10"=>"10", "25"=>"25", "50"=>"50", "75"=>"75", "100"=>"100");
    
    $this->widgetSchema['rec_per_page'] = new sfWidgetFormChoice(array('choices' => $recPerPages), array('class'=>'rec_per_page'));
    $this->setDefault('rec_per_page', strval(sfConfig::get('app_recPerPage'))); 
    $this->widgetSchema->setLabels(array('rec_per_page' => 'Records per page: ',));    
    $this->validatorSchema['rec_per_page'] = new sfValidatorChoice(array('required' => false, 'choices'=>$recPerPages, 'empty_value'=>strval(sfConfig::get('app_recPerPage'))));
    $this->hasPager = true;
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
      if(isset($this->hasPager) && $this->hasPager)
      {
	if(! isset($taintedValues['rec_per_page']))
	{
	  $taintedValues['rec_per_page'] = $this['rec_per_page']->getValue();
	}
      }
      parent::bind($taintedValues, $taintedFiles);
  }

  protected function getDateItemOptions()
  {
    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    return array('culture'=>$this->getCurrentCulture(), 
            'image'=>'/images/calendar.gif',       
            'format' => '%day%/%month%/%year%',    
            'years' => $years,                     
            'empty_values' => $dateText,           
      ); 
  }

  protected function getCatalogueRecLimits()
  {
    return intval(sfConfig::get('app_catalogueRecLimit'));
  }

  public function addNamingColumnQuery(Doctrine_Query $query, $table, $field, $values)
  {
     if ($values != "" && $table != "" && $field != "")
     {
       $words = explode(" ", $values);
       foreach($words as $word)
       {
         $query->andWhere($field . " @@ search_words_to_query('" . $table . "' , '" . $field . "', ? , 'contains') ",$word);
       }
     }
     return $query;
  }

  public function addDateFromToColumnQuery(Doctrine_Query $query, array $dateFields, $val_from, $val_to)
  {
    if (count($dateFields) > 0)
    {
      if($val_from->getMask() > 0 && $val_to->getMask() > 0)
      {
        if (count($dateFields) == 1)
        {
          $query->andWhere($dateFields[0] . " Between ? and ? ",
                           array($val_from->format('d/m/Y'), 
                                 $val_to->format('d/m/Y')
                                )
                          );
        }
        else
        {
          $query->andWhere(" " . $dateFields[0] . " >= ? ", $val_from->format('d/m/Y'))
                ->andWhere(" " . $dateFields[1] . " <= ? ", $val_to->format('d/m/Y'));
        }
      }
      elseif ($val_from->getMask() > 0)
      {
        $sql = " (" . $dateFields[0] . " >= ? AND " . $dateFields[0] . "_mask > 0) ";
        for ($i = 1; $i <= count($dateFields); $i++)
        {
          $vals[] = $val_from->format('d/m/Y');
        }
        if (count($dateFields) > 1) $sql .= " OR (" . $dateFields[1] . " >= ? AND " . $dateFields[1] . "_mask > 0) ";
        $query->andWhere($sql, 
                         $vals
                        );
      } 
      elseif ($val_to->getMask() > 0)
      {
        $sql = " (" . $dateFields[0] . " <= ? AND " . $dateFields[0] . "_mask > 0) ";
        for ($i = 1; $i <= count($dateFields); $i++)
        {
          $vals[] = $val_to->format('d/m/Y');
        }
        if (count($dateFields) > 1) $sql .= " OR (" . $dateFields[1] . " <= ? AND " . $dateFields[1] . "_mask > 0) ";
        $query->andWhere($sql, 
                         $vals
                        );
      }
    }
    return $query;
  }

  protected function getI18N()
  {
     return sfContext::getInstance()->getI18N();
  }

  public function getCurrentCulture()
  {
    return isset($this->options['culture']) ? $this->options['culture'] : 'en';
  }

  public function getJavascripts()
  {
    $javascripts=parent::getJavascripts();
    $javascripts[]='/js/searchForm.js';
    $javascripts[]='/js/pager.js';
    return $javascripts;
  }

}
