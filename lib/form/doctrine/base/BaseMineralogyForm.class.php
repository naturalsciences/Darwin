<?php

/**
 * Mineralogy form base class.
 *
 * @package    form
 * @subpackage mineralogy
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseMineralogyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'name'                  => new sfWidgetFormTextarea(),
      'name_indexed'          => new sfWidgetFormTextarea(),
      'level_ref'             => new sfWidgetFormInput(),
      'status'                => new sfWidgetFormTextarea(),
      'path'                  => new sfWidgetFormTextarea(),
      'parent_ref'            => new sfWidgetFormDoctrineChoice(array('model' => 'Mineralogy', 'add_empty' => false)),
      'code'                  => new sfWidgetFormTextarea(),
      'classification'        => new sfWidgetFormTextarea(),
      'formule'               => new sfWidgetFormTextarea(),
      'formule_indexed'       => new sfWidgetFormTextarea(),
      'cristal_system'        => new sfWidgetFormTextarea(),
      'unit_class_ref'        => new sfWidgetFormInput(),
      'unit_class_indexed'    => new sfWidgetFormTextarea(),
      'unit_division_ref'     => new sfWidgetFormInput(),
      'unit_division_indexed' => new sfWidgetFormTextarea(),
      'unit_family_ref'       => new sfWidgetFormInput(),
      'unit_family_indexed'   => new sfWidgetFormTextarea(),
      'unit_group_ref'        => new sfWidgetFormInput(),
      'unit_group_indexed'    => new sfWidgetFormTextarea(),
      'unit_variety_ref'      => new sfWidgetFormInput(),
      'unit_variety_indexed'  => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => 'Mineralogy', 'column' => 'id', 'required' => false)),
      'name'                  => new sfValidatorString(),
      'name_indexed'          => new sfValidatorString(array('required' => false)),
      'level_ref'             => new sfValidatorInteger(array('required' => false)),
      'status'                => new sfValidatorString(),
      'path'                  => new sfValidatorString(),
      'parent_ref'            => new sfValidatorDoctrineChoice(array('model' => 'Mineralogy')),
      'code'                  => new sfValidatorString(),
      'classification'        => new sfValidatorString(),
      'formule'               => new sfValidatorString(array('required' => false)),
      'formule_indexed'       => new sfValidatorString(array('required' => false)),
      'cristal_system'        => new sfValidatorString(array('required' => false)),
      'unit_class_ref'        => new sfValidatorInteger(),
      'unit_class_indexed'    => new sfValidatorString(),
      'unit_division_ref'     => new sfValidatorInteger(),
      'unit_division_indexed' => new sfValidatorString(),
      'unit_family_ref'       => new sfValidatorInteger(),
      'unit_family_indexed'   => new sfValidatorString(),
      'unit_group_ref'        => new sfValidatorInteger(),
      'unit_group_indexed'    => new sfValidatorString(),
      'unit_variety_ref'      => new sfValidatorInteger(),
      'unit_variety_indexed'  => new sfValidatorString(),
    ));

    $this->widgetSchema->setNameFormat('mineralogy[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Mineralogy';
  }

}
