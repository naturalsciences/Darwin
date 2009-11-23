<?php
class SearchTaxonForm extends sfForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'name'    => new sfWidgetFormInput(),
      ));

    $this->setValidators(array(
      'name'    => new sfValidatorString(array(
		      'required' => true,
		      'trim' => true)),
      ));
    $this->widgetSchema->setNameFormat('searchTaxon[%s]');
  }
}