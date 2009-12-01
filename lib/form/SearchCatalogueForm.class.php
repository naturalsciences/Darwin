<?php
class SearchCatalogueForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'name'    => new sfWidgetFormInputText(),
      'table'    => new sfWidgetFormInputHidden(),
      ));

    $this->setValidators(array(
      'name'    => new sfValidatorString(array(
		      'required' => true,
		      'trim' => true)),
      'table'   => new sfValidatorString(array('required' => true)),
      ));
    $this->widgetSchema->setNameFormat('searchTaxon[%s]');
  }
}