<?php

/**
 * Collections form base class.
 *
 * @package    form
 * @subpackage collections
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCollectionsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                       => new sfWidgetFormInputHidden(),
      'collection_type'          => new sfWidgetFormChoice(array('choices' => array('mix' => 'mix', 'observation' => 'observation', 'physical' => 'physical'))),
      'code'                     => new sfWidgetFormTextarea(),
      'name'                     => new sfWidgetFormTextarea(),
      'institution_ref'          => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => false)),
      'main_manager_ref'         => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
      'parent_ref'               => new sfWidgetFormDoctrineChoice(array('model' => 'Collections', 'add_empty' => true)),
      'path'                     => new sfWidgetFormTextarea(),
      'code_auto_increment'      => new sfWidgetFormInputCheckbox(),
      'code_part_code_auto_copy' => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                       => new sfValidatorDoctrineChoice(array('model' => 'Collections', 'column' => 'id', 'required' => false)),
      'collection_type'          => new sfValidatorChoice(array('choices' => array('mix' => 'mix', 'observation' => 'observation', 'physical' => 'physical'))),
      'code'                     => new sfValidatorString(),
      'name'                     => new sfValidatorString(),
      'institution_ref'          => new sfValidatorDoctrineChoice(array('model' => 'People')),
      'main_manager_ref'         => new sfValidatorDoctrineChoice(array('model' => 'Users')),
      'parent_ref'               => new sfValidatorDoctrineChoice(array('model' => 'Collections', 'required' => false)),
      'path'                     => new sfValidatorString(),
      'code_auto_increment'      => new sfValidatorBoolean(),
      'code_part_code_auto_copy' => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('collections[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Collections';
  }

}
