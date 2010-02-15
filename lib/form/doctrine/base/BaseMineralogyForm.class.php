<?php

/**
 * Mineralogy form base class.
 *
 * @method Mineralogy getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseMineralogyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'name'                  => new sfWidgetFormTextarea(),
      'name_indexed'          => new sfWidgetFormTextarea(),
      'level_ref'             => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Level'), 'add_empty' => false)),
      'status'                => new sfWidgetFormTextarea(),
      'path'                  => new sfWidgetFormTextarea(),
      'parent_ref'            => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => true)),
      'code'                  => new sfWidgetFormTextarea(),
      'classification'        => new sfWidgetFormTextarea(),
      'formule'               => new sfWidgetFormTextarea(),
      'formule_indexed'       => new sfWidgetFormTextarea(),
      'cristal_system'        => new sfWidgetFormTextarea(),
      'unit_class_ref'        => new sfWidgetFormInputText(),
      'unit_class_indexed'    => new sfWidgetFormTextarea(),
      'unit_division_ref'     => new sfWidgetFormInputText(),
      'unit_division_indexed' => new sfWidgetFormTextarea(),
      'unit_family_ref'       => new sfWidgetFormInputText(),
      'unit_family_indexed'   => new sfWidgetFormTextarea(),
      'unit_group_ref'        => new sfWidgetFormInputText(),
      'unit_group_indexed'    => new sfWidgetFormTextarea(),
      'unit_variety_ref'      => new sfWidgetFormInputText(),
      'unit_variety_indexed'  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'                  => new sfValidatorString(),
      'name_indexed'          => new sfValidatorString(array('required' => false)),
      'level_ref'             => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Level'))),
      'status'                => new sfValidatorString(array('required' => false)),
      'path'                  => new sfValidatorString(array('required' => false)),
      'parent_ref'            => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'required' => false)),
      'code'                  => new sfValidatorString(),
      'classification'        => new sfValidatorString(array('required' => false)),
      'formule'               => new sfValidatorString(array('required' => false)),
      'formule_indexed'       => new sfValidatorString(array('required' => false)),
      'cristal_system'        => new sfValidatorString(array('required' => false)),
      'unit_class_ref'        => new sfValidatorInteger(array('required' => false)),
      'unit_class_indexed'    => new sfValidatorString(array('required' => false)),
      'unit_division_ref'     => new sfValidatorInteger(array('required' => false)),
      'unit_division_indexed' => new sfValidatorString(array('required' => false)),
      'unit_family_ref'       => new sfValidatorInteger(array('required' => false)),
      'unit_family_indexed'   => new sfValidatorString(array('required' => false)),
      'unit_group_ref'        => new sfValidatorInteger(array('required' => false)),
      'unit_group_indexed'    => new sfValidatorString(array('required' => false)),
      'unit_variety_ref'      => new sfValidatorInteger(array('required' => false)),
      'unit_variety_indexed'  => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('mineralogy[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Mineralogy';
  }

}
