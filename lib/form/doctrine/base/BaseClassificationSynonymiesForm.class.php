<?php

/**
 * ClassificationSynonymies form base class.
 *
 * @package    form
 * @subpackage classification_synonymies
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseClassificationSynonymiesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'referenced_relation' => new sfWidgetFormTextarea(),
      'record_id'           => new sfWidgetFormInput(),
      'group_id'            => new sfWidgetFormInput(),
      'group_name'          => new sfWidgetFormTextarea(),
      'basionym_record_id'  => new sfWidgetFormInput(),
      'order_by'            => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => 'ClassificationSynonymies', 'column' => 'id', 'required' => false)),
      'referenced_relation' => new sfValidatorString(),
      'record_id'           => new sfValidatorInteger(),
      'group_id'            => new sfValidatorInteger(),
      'group_name'          => new sfValidatorString(),
      'basionym_record_id'  => new sfValidatorInteger(array('required' => false)),
      'order_by'            => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('classification_synonymies[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClassificationSynonymies';
  }

}
