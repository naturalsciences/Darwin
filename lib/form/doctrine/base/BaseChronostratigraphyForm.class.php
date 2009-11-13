<?php

/**
 * Chronostratigraphy form base class.
 *
 * @package    form
 * @subpackage chronostratigraphy
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseChronostratigraphyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'name'                => new sfWidgetFormTextarea(),
      'name_indexed'        => new sfWidgetFormTextarea(),
      'level_ref'           => new sfWidgetFormInput(),
      'status'              => new sfWidgetFormTextarea(),
      'path'                => new sfWidgetFormTextarea(),
      'parent_ref'          => new sfWidgetFormDoctrineChoice(array('model' => 'Chronostratigraphy', 'add_empty' => false)),
      'eon_ref'             => new sfWidgetFormInput(),
      'eon_indexed'         => new sfWidgetFormTextarea(),
      'era_ref'             => new sfWidgetFormInput(),
      'era_indexed'         => new sfWidgetFormTextarea(),
      'sub_era_ref'         => new sfWidgetFormInput(),
      'sub_era_indexed'     => new sfWidgetFormTextarea(),
      'system_ref'          => new sfWidgetFormInput(),
      'system_indexed'      => new sfWidgetFormTextarea(),
      'serie_ref'           => new sfWidgetFormInput(),
      'serie_indexed'       => new sfWidgetFormTextarea(),
      'stage_ref'           => new sfWidgetFormInput(),
      'stage_indexed'       => new sfWidgetFormTextarea(),
      'sub_stage_ref'       => new sfWidgetFormInput(),
      'sub_stage_indexed'   => new sfWidgetFormTextarea(),
      'sub_level_1_ref'     => new sfWidgetFormInput(),
      'sub_level_1_indexed' => new sfWidgetFormTextarea(),
      'sub_level_2_ref'     => new sfWidgetFormInput(),
      'sub_level_2_indexed' => new sfWidgetFormTextarea(),
      'lower_bound'         => new sfWidgetFormInput(),
      'upper_bound'         => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => 'Chronostratigraphy', 'column' => 'id', 'required' => false)),
      'name'                => new sfValidatorString(),
      'name_indexed'        => new sfValidatorString(array('required' => false)),
      'level_ref'           => new sfValidatorInteger(array('required' => false)),
      'status'              => new sfValidatorString(),
      'path'                => new sfValidatorString(),
      'parent_ref'          => new sfValidatorDoctrineChoice(array('model' => 'Chronostratigraphy')),
      'eon_ref'             => new sfValidatorInteger(),
      'eon_indexed'         => new sfValidatorString(),
      'era_ref'             => new sfValidatorInteger(),
      'era_indexed'         => new sfValidatorString(),
      'sub_era_ref'         => new sfValidatorInteger(),
      'sub_era_indexed'     => new sfValidatorString(),
      'system_ref'          => new sfValidatorInteger(),
      'system_indexed'      => new sfValidatorString(),
      'serie_ref'           => new sfValidatorInteger(),
      'serie_indexed'       => new sfValidatorString(),
      'stage_ref'           => new sfValidatorInteger(),
      'stage_indexed'       => new sfValidatorString(),
      'sub_stage_ref'       => new sfValidatorInteger(),
      'sub_stage_indexed'   => new sfValidatorString(),
      'sub_level_1_ref'     => new sfValidatorInteger(),
      'sub_level_1_indexed' => new sfValidatorString(),
      'sub_level_2_ref'     => new sfValidatorInteger(),
      'sub_level_2_indexed' => new sfValidatorString(),
      'lower_bound'         => new sfValidatorInteger(array('required' => false)),
      'upper_bound'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('chronostratigraphy[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Chronostratigraphy';
  }

}
