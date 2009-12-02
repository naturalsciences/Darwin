<?php

/**
 * ClassificationSynonymies form base class.
 *
 * @method ClassificationSynonymies getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseClassificationSynonymiesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'referenced_relation' => new sfWidgetFormTextarea(),
      'record_id'           => new sfWidgetFormInputText(),
      'group_id'            => new sfWidgetFormInputText(),
      'group_name'          => new sfWidgetFormTextarea(),
      'basionym_record_id'  => new sfWidgetFormInputText(),
      'order_by'            => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'referenced_relation' => new sfValidatorString(),
      'record_id'           => new sfValidatorInteger(),
      'group_id'            => new sfValidatorInteger(),
      'group_name'          => new sfValidatorString(),
      'basionym_record_id'  => new sfValidatorInteger(array('required' => false)),
      'order_by'            => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('classification_synonymies[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClassificationSynonymies';
  }

}
