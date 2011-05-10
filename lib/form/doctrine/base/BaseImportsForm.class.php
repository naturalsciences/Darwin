<?php

/**
 * Imports form base class.
 *
 * @method Imports getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseImportsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'filename'       => new sfWidgetFormTextarea(),
      'user_ref'       => new sfWidgetFormInputText(),
      'format'         => new sfWidgetFormTextarea(),
      'collection_ref' => new sfWidgetFormInputText(),
      'state'          => new sfWidgetFormTextarea(),
      'created_at'     => new sfWidgetFormTextarea(),
      'updated_at'     => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'filename'       => new sfValidatorString(),
      'user_ref'       => new sfValidatorInteger(),
      'format'         => new sfValidatorString(),
      'collection_ref' => new sfValidatorInteger(array('required' => false)),
      'state'          => new sfValidatorString(array('required' => false)),
      'created_at'     => new sfValidatorString(),
      'updated_at'     => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('imports[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Imports';
  }

}
