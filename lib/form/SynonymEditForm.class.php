<?php 
class SynonymEditForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'orders'    => new sfWidgetFormInputHidden(),
      'basionym_id'   => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'orders'    => new sfValidatorString(array('required' => false, 'trim' => true)),
      'basionym_id'   => new sfValidatorString(array('required' => false, 'trim' => true)),
    ));

    $this->widgetSchema->setNameFormat('synonym_edit[%s]');
  }
}