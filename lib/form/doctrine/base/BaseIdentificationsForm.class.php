<?php

/**
 * Identifications form base class.
 *
 * @method Identifications getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseIdentificationsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'referenced_relation'   => new sfWidgetFormTextarea(),
      'record_id'             => new sfWidgetFormInputText(),
      'notion_concerned'      => new sfWidgetFormTextarea(),
      'notion_date'           => new sfWidgetFormDateTime(),
      'value_defined'         => new sfWidgetFormTextarea(),
      'value_defined_indexed' => new sfWidgetFormTextarea(),
      'value_defined_ts'      => new sfWidgetFormTextarea(),
      'determination_status'  => new sfWidgetFormTextarea(),
      'order_by'              => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'referenced_relation'   => new sfValidatorString(),
      'record_id'             => new sfValidatorInteger(),
      'notion_concerned'      => new sfValidatorString(),
      'notion_date'           => new sfValidatorDateTime(array('required' => false)),
      'value_defined'         => new sfValidatorString(array('required' => false)),
      'value_defined_indexed' => new sfValidatorString(array('required' => false)),
      'value_defined_ts'      => new sfValidatorString(array('required' => false)),
      'determination_status'  => new sfValidatorString(array('required' => false)),
      'order_by'              => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('identifications[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Identifications';
  }

}
