<?php

/**
 * Codes form base class.
 *
 * @method Codes getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCodesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'referenced_relation'   => new sfWidgetFormTextarea(),
      'record_id'             => new sfWidgetFormInputText(),
      'code_category'         => new sfWidgetFormTextarea(),
      'code_prefix'           => new sfWidgetFormTextarea(),
      'code_prefix_separator' => new sfWidgetFormTextarea(),
      'code'                  => new sfWidgetFormInputText(),
      'code_suffix'           => new sfWidgetFormTextarea(),
      'code_suffix_separator' => new sfWidgetFormTextarea(),
      'full_code_indexed'     => new sfWidgetFormTextarea(),
      'full_code_order_by'    => new sfWidgetFormTextarea(),
      'code_date'             => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'referenced_relation'   => new sfValidatorString(array('required' => false)),
      'record_id'             => new sfValidatorInteger(),
      'code_category'         => new sfValidatorString(array('required' => false)),
      'code_prefix'           => new sfValidatorString(array('required' => false)),
      'code_prefix_separator' => new sfValidatorString(array('required' => false)),
      'code'                  => new sfValidatorInteger(array('required' => false)),
      'code_suffix'           => new sfValidatorString(array('required' => false)),
      'code_suffix_separator' => new sfValidatorString(array('required' => false)),
      'full_code_indexed'     => new sfValidatorString(array('required' => false)),
      'full_code_order_by'    => new sfValidatorString(array('required' => false)),
      'code_date'             => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('codes[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Codes';
  }

}
