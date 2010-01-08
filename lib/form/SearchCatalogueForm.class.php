<?php
class SearchCatalogueForm extends DarwinForm
{
  public function configure()
  {
    $recPerPages = array_combine(self::$recPerPages, self::$recPerPages);

    $this->setWidgets(array(
      'name'    => new sfWidgetFormInputText(),
      'table'    => new sfWidgetFormInputHidden(),
      'rec_per_page' => new sfWidgetFormChoice(array('choices' => $recPerPages,'expanded'=>false)),
      ));

    $this->setValidators(array(
      'name'    => new sfValidatorString(array(
		      'required' => true,
		      'trim' => true)),
      'table'   => new sfValidatorString(array('required' => true)),
    'rec_per_page' => new sfValidatorChoice(array('required' => false, 'choices'=>$recPerPages, 'empty_value'=>strval(sfConfig::get('app_recPerPage')))),
      ));

    $this->setDefault('rec_per_page', strval(sfConfig::get('app_recPerPage'))); 
    $this->widgetSchema->setNameFormat('searchTaxon[%s]');
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
      if(! isset($taintedValues['rec_per_page']))
      {
	$taintedValues['rec_per_page'] = $this['rec_per_page']->getValue();
      }
      parent::bind($taintedValues, $taintedFiles);
  }
}