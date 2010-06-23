<?php

/**
 * Gtu form.
 *
 * @package    form
 * @subpackage Gtu
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class GtuForm extends BaseGtuForm
{
  public function configure()
  {
    unset($this['gtu_from_date_mask'], 
          $this['gtu_to_date_mask']
         );

    $this->widgetSchema['parent_ref'] = new widgetFormButtonRef(array(
       'model' => 'Gtu',
       'method' => 'getName',
       'link_url' => 'gtu/choose',
       'box_title' => $this->getI18N()->__('Choose Parent'),
       'nullable' => true,
     ));
    $this->widgetSchema['code'] = new sfWidgetFormInput();
    $yearsKeyVal = range(intval(sfConfig::get('app_yearRangeMin')), intval(sfConfig::get('app_yearRangeMax')));
    $years = array_combine($yearsKeyVal, $yearsKeyVal);
    $dateText = array('year'=>'yyyy', 'month'=>'mm', 'day'=>'dd');
    $minDate = new FuzzyDateTime(strval(min($yearsKeyVal).'/01/01'));
    $maxDate = new FuzzyDateTime(strval(max($yearsKeyVal).'/12/31'));
    $dateLowerBound = new FuzzyDateTime(sfConfig::get('app_dateLowerBound'));
    $dateUpperBound = new FuzzyDateTime(sfConfig::get('app_dateUpperBound'));
    $maxDate->setStart(false);

    $this->widgetSchema['name'] = new sfWidgetFormInputText();
    $this->widgetSchema['gtu_from_date'] = new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                                      'image'=>'/images/calendar.gif', 
                                                                                      'format' => '%day%/%month%/%year%', 
                                                                                      'years' => $years,
                                                                                      'empty_values' => $dateText,
                                                                                     ),
                                                                                array('class' => 'from_date')
                                                                               );
    $this->widgetSchema['gtu_to_date'] = new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                                                    'image'=>'/images/calendar.gif', 
                                                                                    'format' => '%day%/%month%/%year%', 
                                                                                    'years' => $years,
                                                                                    'empty_values' => $dateText, 
                                                                                   ),
                                                                              array('class' => 'to_date')
                                                                             );
    $this->validatorSchema['gtu_from_date'] = new fuzzyDateValidator(array('required' => false,
                                                                                  'from_date' => true,
                                                                                  'min' => $minDate,
                                                                                  'max' => $maxDate, 
                                                                                  'empty_value' => $dateLowerBound,
                                                                                 ),
                                                                            array('invalid' => 'Date provided is not valid',
                                                                                 )
                                                                           );
    $this->validatorSchema['gtu_to_date'] = new fuzzyDateValidator(array('required' => false,
                                                                                'from_date' => false,
                                                                                'min' => $minDate,
                                                                                'max' => $maxDate,
                                                                                'empty_value' => $dateUpperBound,
                                                                               ),
                                                                          array('invalid' => 'Date provided is not valid',
                                                                               )
                                                                         );
    $this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('gtu_from_date', 
                                                                          '<=', 
                                                                          'gtu_to_date', 
                                                                          array('throw_global_error' => true), 
                                                                          array('invalid'=>'The "begin" date cannot be above the "end" date.')
                                                                         )
                                            ); 
    $subForm = new sfForm();
    $this->embedForm('newVal',$subForm);
    $this->embedRelation('TagGroups');
    $this->embedForm('TagGroups', $this->embeddedForms['TagGroups']);

  }
  
  public function addValue($num, $group="")
  {
      $val = new TagGroups();
      if($group != '')
	$val->setGroupName($group);

      $val->Gtu = $this->getObject();
      $form = new TagGroupsForm($val);
  
      $this->embeddedForms['newVal']->embedForm($num, $form);
      //Re-embedding the container
      $this->embedForm('newVal', $this->embeddedForms['newVal']);
   }

    public function bind(array $taintedValues = null, array $taintedFiles = null)
    {
      if(isset($taintedValues['newVal']))
      {
	foreach($taintedValues['newVal'] as $key=>$newVal)
	{
	  if (!isset($this['newVal'][$key]))
	  {
	    $this->addValue($key);
	  }
	}
      }
      parent::bind($taintedValues, $taintedFiles);
    }

    public function saveEmbeddedForms($con = null, $forms = null)
    {

      if (null === $forms)
      {
	$value = $this->getValue('newVal');
	foreach($this->embeddedForms['newVal']->getEmbeddedForms() as $name => $form)
	{
	  if (!isset($value[$name]['tag_value'])  || $value[$name]['tag_value'] == '')
	  {
	    unset($this->embeddedForms['newVal'][$name]);
	  }
	}

	$value = $this->getValue('TagGroups');
	foreach($this->embeddedForms['TagGroups']->getEmbeddedForms() as $name => $form)
	{
	  
	  if (!isset($value[$name]['tag_value']) || $value[$name]['tag_value'] == '' )
	  {
	    $form->getObject()->delete();
	    unset($this->embeddedForms['TagGroups'][$name]);
	  }
	}
      }
      return parent::saveEmbeddedForms($con, $forms);
    }
}