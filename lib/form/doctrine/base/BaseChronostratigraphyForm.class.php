<?php

/**
 * Chronostratigraphy form base class.
 *
 * @method Chronostratigraphy getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseChronostratigraphyForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'name'                => new sfWidgetFormTextarea(),
      'name_indexed'        => new sfWidgetFormTextarea(),
      'level_ref'           => new sfWidgetFormInputText(),
      'status'              => new sfWidgetFormTextarea(),
      'path'                => new sfWidgetFormTextarea(),
      'parent_ref'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'add_empty' => false)),
      'eon_ref'             => new sfWidgetFormInputText(),
      'eon_indexed'         => new sfWidgetFormTextarea(),
      'era_ref'             => new sfWidgetFormInputText(),
      'era_indexed'         => new sfWidgetFormTextarea(),
      'sub_era_ref'         => new sfWidgetFormInputText(),
      'sub_era_indexed'     => new sfWidgetFormTextarea(),
      'system_ref'          => new sfWidgetFormInputText(),
      'system_indexed'      => new sfWidgetFormTextarea(),
      'serie_ref'           => new sfWidgetFormInputText(),
      'serie_indexed'       => new sfWidgetFormTextarea(),
      'stage_ref'           => new sfWidgetFormInputText(),
      'stage_indexed'       => new sfWidgetFormTextarea(),
      'sub_stage_ref'       => new sfWidgetFormInputText(),
      'sub_stage_indexed'   => new sfWidgetFormTextarea(),
      'sub_level_1_ref'     => new sfWidgetFormInputText(),
      'sub_level_1_indexed' => new sfWidgetFormTextarea(),
      'sub_level_2_ref'     => new sfWidgetFormInputText(),
      'sub_level_2_indexed' => new sfWidgetFormTextarea(),
      'lower_bound'         => new sfWidgetFormInputText(),
      'upper_bound'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'name'                => new sfValidatorString(),
      'name_indexed'        => new sfValidatorString(array('required' => false)),
      'level_ref'           => new sfValidatorInteger(array('required' => false)),
      'status'              => new sfValidatorString(array('required' => false)),
      'path'                => new sfValidatorString(array('required' => false)),
      'parent_ref'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Parent'), 'required' => false)),
      'eon_ref'             => new sfValidatorInteger(array('required' => false)),
      'eon_indexed'         => new sfValidatorString(array('required' => false)),
      'era_ref'             => new sfValidatorInteger(array('required' => false)),
      'era_indexed'         => new sfValidatorString(array('required' => false)),
      'sub_era_ref'         => new sfValidatorInteger(array('required' => false)),
      'sub_era_indexed'     => new sfValidatorString(array('required' => false)),
      'system_ref'          => new sfValidatorInteger(array('required' => false)),
      'system_indexed'      => new sfValidatorString(array('required' => false)),
      'serie_ref'           => new sfValidatorInteger(array('required' => false)),
      'serie_indexed'       => new sfValidatorString(array('required' => false)),
      'stage_ref'           => new sfValidatorInteger(array('required' => false)),
      'stage_indexed'       => new sfValidatorString(array('required' => false)),
      'sub_stage_ref'       => new sfValidatorInteger(array('required' => false)),
      'sub_stage_indexed'   => new sfValidatorString(array('required' => false)),
      'sub_level_1_ref'     => new sfValidatorInteger(array('required' => false)),
      'sub_level_1_indexed' => new sfValidatorString(array('required' => false)),
      'sub_level_2_ref'     => new sfValidatorInteger(array('required' => false)),
      'sub_level_2_indexed' => new sfValidatorString(array('required' => false)),
      'lower_bound'         => new sfValidatorInteger(array('required' => false)),
      'upper_bound'         => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('chronostratigraphy[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Chronostratigraphy';
  }

}
