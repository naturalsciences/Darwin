<?php

/**
 * Gtu form base class.
 *
 * @method Gtu getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseGtuForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'code'               => new sfWidgetFormTextarea(),
      'parent_ref'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => false)),
      'gtu_from_date_mask' => new sfWidgetFormInputText(),
      'gtu_from_date'      => new sfWidgetFormDateTime(),
      'gtu_to_date_mask'   => new sfWidgetFormInputText(),
      'gtu_to_date'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'code'               => new sfValidatorString(),
      'parent_ref'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'))),
      'gtu_from_date_mask' => new sfValidatorInteger(array('required' => false)),
      'gtu_from_date'      => new sfValidatorDateTime(array('required' => false)),
      'gtu_to_date_mask'   => new sfValidatorInteger(array('required' => false)),
      'gtu_to_date'        => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gtu[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Gtu';
  }

}
