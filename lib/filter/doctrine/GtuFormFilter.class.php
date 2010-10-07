<?php

/**
 * Gtu filter form.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class GtuFormFilter extends BaseGtuFormFilter
{
  public function configure()
  {

    $this->useFields(array('code', 'gtu_from_date', 'gtu_to_date'));
    $this->addPagerItems();
    $minDate = new FuzzyDateTime(strval(min(range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')))).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max(range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')))).'/12/31'));
    $maxDate->setStart(false);
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $dateUpperBound = new FuzzyDateTime(sfConfig::get('app_dateUpperBound'));
    $this->widgetSchema['tags'] = new sfWidgetFormInputText();
    $this->widgetSchema['gtu_from_date'] = new widgetFormJQueryFuzzyDate($this->getDateItemOptions(),
                                                                                array('class' => 'from_date')
                                                                               );
    $this->widgetSchema['gtu_to_date'] = new widgetFormJQueryFuzzyDate($this->getDateItemOptions(),
                                                                              array('class' => 'to_date')
                                                                             );
    $this->widgetSchema->setLabels(array('gtu_from_date' => 'Between',
                                         'gtu_to_date' => 'and',
                                        )
                                  );
    $this->validatorSchema['tags'] = new sfValidatorString(array('required' => false, 'trim' => true));
    $this->validatorSchema['gtu_from_date'] = new fuzzyDateValidator(array('required' => false,
                                                                                  'from_date' => true,
                                                                                  'min' => $minDate,
                                                                                  'max' => $maxDate, 
                                                                                  'empty_value' => $dateLowerBound,
                                                                                 ),
                                                                            array('invalid' => 'Date provided is not valid',)
                                                                           );
    $this->validatorSchema['gtu_to_date'] = new fuzzyDateValidator(array('required' => false,
                                                                                'from_date' => false,
                                                                                'min' => $minDate,
                                                                                'max' => $maxDate,
                                                                                'empty_value' => $dateUpperBound,
                                                                               ),
                                                                          array('invalid' => 'Date provided is not valid',)
                                                                         );
    $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('gtu_from_date', 
                                                                          '<=', 
                                                                          'gtu_to_date', 
                                                                          array('throw_global_error' => true), 
                                                                          array('invalid'=>'The "begin" date cannot be above the "end" date.')
                                                                         )
                                            );
    $subForm = new sfForm();
    $this->embedForm('Tags',$subForm);
  }

  public function addTagsColumnQuery($query, $field, $val)
  {
    $alias = $query->getRootAlias();
    $conn_MGR = Doctrine_Manager::connection();
    $tagList = '';

    foreach($val as $line)
    {
      $line_val = $line['tag'];
      if( $line_val != '')
      {
        $tagList = $conn_MGR->quote($line_val, 'string');
        $query->andWhere("tag_values_indexed && getTagsIndexedAsArray($tagList)");
      }
    }
/*    if(strlen($tagList))
    {
      $tagList = substr($tagList, 0, -1); //remove last ','
      $query->andWhere("id in (select getGtusForTags(array[$tagList]))");
    }*/
    return $query;
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if(isset($taintedValues['Tags']))
    {
      foreach($taintedValues['Tags'] as $key=>$newVal)
      {
        if (!isset($this['Tags'][$key]))
        {
          $this->addValue($key);
        }
      }
    }
    parent::bind($taintedValues, $taintedFiles);
  }

  public function addValue($num)
  {
      $form = new TagLineForm(null,array('num'=>$num));
      $this->embeddedForms['Tags']->embedForm($num, $form);
      $this->embedForm('Tags', $this->embeddedForms['Tags']);
  }

  public function doBuildQuery(array $values)
  {
    $query = parent::doBuildQuery($values);
    $alias = $query->getRootAlias();
    $query
      ->leftJoin($alias.'.TagGroups g');
//       ->leftJoin('g.Tags');
    $fields = array('gtu_from_date', 'gtu_to_date');
    $this->addDateFromToColumnQuery($query, $fields, $values['gtu_from_date'], $values['gtu_to_date']);
    $query->andWhere("id > 0 ");
    return $query;
  }
}
