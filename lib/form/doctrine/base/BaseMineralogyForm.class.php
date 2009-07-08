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
      'id'                     => new sfWidgetFormInputHidden(),
      'name'                   => new sfWidgetFormTextarea(),
      'name_indexed'           => new sfWidgetFormTextarea(),
      'description_year'       => new sfWidgetFormInput(),
      'description_year_compl' => new sfWidgetFormInput(),
      'level_ref'              => new sfWidgetFormInput(),
      'status'                 => new sfWidgetFormTextarea(),
      'path'                   => new sfWidgetFormTextarea(),
      'parent_ref'             => new sfWidgetFormDoctrineChoice(array('model' => 'Mineralogy', 'add_empty' => false)),
      'code'                   => new sfWidgetFormTextarea(),
      'classification'         => new sfWidgetFormTextarea(),
      'formule'                => new sfWidgetFormTextarea(),
      'formule_indexed'        => new sfWidgetFormTextarea(),
      'cristal_system'         => new sfWidgetFormTextarea(),
      'unit_class_ref'         => new sfWidgetFormInput(),
      'unit_class_indexed'     => new sfWidgetFormTextarea(),
      'unit_division_ref'      => new sfWidgetFormInput(),
      'unit_division_indexed'  => new sfWidgetFormTextarea(),
      'unit_family_ref'        => new sfWidgetFormInput(),
      'unit_family_indexed'    => new sfWidgetFormTextarea(),
      'unit_group_ref'         => new sfWidgetFormInput(),
      'unit_group_indexed'     => new sfWidgetFormTextarea(),
      'unit_variety_ref'       => new sfWidgetFormInput(),
      'unit_variety_indexed'   => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorDoctrineChoice(array('model' => 'Mineralogy', 'column' => 'id', 'required' => false)),
      'name'                   => new sfValidatorString(array('max_length' => 2147483647)),
      'name_indexed'           => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'description_year'       => new sfValidatorInteger(array('required' => false)),
      'description_year_compl' => new sfValidatorString(array('max_length' => 2, 'required' => false)),
      'level_ref'              => new sfValidatorInteger(array('required' => false)),
      'status'                 => new sfValidatorString(array('max_length' => 2147483647)),
      'path'                   => new sfValidatorString(array('max_length' => 2147483647)),
      'parent_ref'             => new sfValidatorDoctrineChoice(array('model' => 'Mineralogy')),
      'code'                   => new sfValidatorString(array('max_length' => 2147483647)),
      'classification'         => new sfValidatorString(array('max_length' => 2147483647)),
      'formule'                => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'formule_indexed'        => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'cristal_system'         => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'unit_class_ref'         => new sfValidatorInteger(),
      'unit_class_indexed'     => new sfValidatorString(array('max_length' => 2147483647)),
      'unit_division_ref'      => new sfValidatorInteger(),
      'unit_division_indexed'  => new sfValidatorString(array('max_length' => 2147483647)),
      'unit_family_ref'        => new sfValidatorInteger(),
      'unit_family_indexed'    => new sfValidatorString(array('max_length' => 2147483647)),
      'unit_group_ref'         => new sfValidatorInteger(),
      'unit_group_indexed'     => new sfValidatorString(array('max_length' => 2147483647)),
      'unit_variety_ref'       => new sfValidatorInteger(),
      'unit_variety_indexed'   => new sfValidatorString(array('max_length' => 2147483647)),
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
