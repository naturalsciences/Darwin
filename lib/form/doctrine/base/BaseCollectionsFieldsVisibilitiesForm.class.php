<?php

/**
 * CollectionsFieldsVisibilities form base class.
 *
 * @package    form
 * @subpackage collections_fields_visibilities
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCollectionsFieldsVisibilitiesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'collection_ref'   => new sfWidgetFormDoctrineChoice(array('model' => 'Collections', 'add_empty' => false)),
      'user_ref'         => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
      'field_group_name' => new sfWidgetFormTextarea(),
      'db_user_type'     => new sfWidgetFormInput(),
      'searchable'       => new sfWidgetFormInputCheckbox(),
      'visible'          => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => 'CollectionsFieldsVisibilities', 'column' => 'id', 'required' => false)),
      'collection_ref'   => new sfValidatorDoctrineChoice(array('model' => 'Collections')),
      'user_ref'         => new sfValidatorDoctrineChoice(array('model' => 'Users')),
      'field_group_name' => new sfValidatorString(),
      'db_user_type'     => new sfValidatorInteger(),
      'searchable'       => new sfValidatorBoolean(),
      'visible'          => new sfValidatorBoolean(),
    ));

    $this->widgetSchema->setNameFormat('collections_fields_visibilities[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectionsFieldsVisibilities';
  }

}
