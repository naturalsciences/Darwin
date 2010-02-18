<?php

/**
 * Lithology form base class.
 *
 * @method Lithology getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseLithologyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'name'                    => new sfWidgetFormTextarea(),
      'name_indexed'            => new sfWidgetFormTextarea(),
      'name_order_by'           => new sfWidgetFormTextarea(),
      'level_ref'               => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Level'), 'add_empty' => false)),
      'status'                  => new sfWidgetFormTextarea(),
      'path'                    => new sfWidgetFormTextarea(),
      'parent_ref'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'unit_main_group_ref'     => new sfWidgetFormInputText(),
      'unit_main_group_indexed' => new sfWidgetFormTextarea(),
      'unit_group_ref'          => new sfWidgetFormInputText(),
      'unit_group_indexed'      => new sfWidgetFormTextarea(),
      'unit_sub_group_ref'      => new sfWidgetFormInputText(),
      'unit_sub_group_indexed'  => new sfWidgetFormTextarea(),
      'unit_rock_ref'           => new sfWidgetFormInputText(),
      'unit_rock_indexed'       => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'                    => new sfValidatorString(),
      'name_indexed'            => new sfValidatorString(array('required' => false)),
      'name_order_by'           => new sfValidatorString(array('required' => false)),
      'level_ref'               => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Level'))),
      'status'                  => new sfValidatorString(array('required' => false)),
      'path'                    => new sfValidatorString(array('required' => false)),
      'parent_ref'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'required' => false)),
      'unit_main_group_ref'     => new sfValidatorInteger(array('required' => false)),
      'unit_main_group_indexed' => new sfValidatorString(array('required' => false)),
      'unit_group_ref'          => new sfValidatorInteger(array('required' => false)),
      'unit_group_indexed'      => new sfValidatorString(array('required' => false)),
      'unit_sub_group_ref'      => new sfValidatorInteger(array('required' => false)),
      'unit_sub_group_indexed'  => new sfValidatorString(array('required' => false)),
      'unit_rock_ref'           => new sfValidatorInteger(array('required' => false)),
      'unit_rock_indexed'       => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('lithology[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Lithology';
  }

}
