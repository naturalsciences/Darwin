<?php

/**
 * Insurances form base class.
 *
 * @method Insurances getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseInsurancesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'referenced_relation' => new sfWidgetFormTextarea(),
      'record_id'           => new sfWidgetFormInputText(),
      'insurance_value'     => new sfWidgetFormInputText(),
      'insurance_currency'  => new sfWidgetFormTextarea(),
      'insurance_year'      => new sfWidgetFormInputText(),
      'insurer_ref'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('People'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'referenced_relation' => new sfValidatorString(),
      'record_id'           => new sfValidatorInteger(),
      'insurance_value'     => new sfValidatorNumber(),
      'insurance_currency'  => new sfValidatorString(array('required' => false)),
      'insurance_year'      => new sfValidatorInteger(array('required' => false)),
      'insurer_ref'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('People'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('insurances[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Insurances';
  }

}
