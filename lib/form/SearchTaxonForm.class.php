<?php
class SearchTaxonForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'level'    => new sfWidgetFormDoctrineChoice(array(
	  'model' => 'CatalogueLevels',
	  'table_method' => 'getLevelsForTaxo',
	  'add_empty' => true
	)),
      'name'    => new sfWidgetFormInput(),
      ));

    $this->setValidators(array(
      'name'    => new sfValidatorString(array(
		      'required' => true,
		      'trim' => true)),
      'level'   => new sfValidatorPass(),
      ));
    $this->widgetSchema->setNameFormat('searchTaxon[%s]');
  }
}