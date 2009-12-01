<?php

/**
 * CollectionsFieldsVisibilities form base class.
 *
 * @method CollectionsFieldsVisibilities getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseCollectionsFieldsVisibilitiesForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'collection_ref'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Collections'), 'add_empty' => false)),
      'user_ref'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => false)),
      'field_group_name' => new sfWidgetFormTextarea(),
      'db_user_type'     => new sfWidgetFormInputText(),
      'searchable'       => new sfWidgetFormInputCheckbox(),
      'visible'          => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'collection_ref'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Collections'), 'required' => false)),
      'user_ref'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
      'field_group_name' => new sfValidatorString(),
      'db_user_type'     => new sfValidatorInteger(array('required' => false)),
      'searchable'       => new sfValidatorBoolean(array('required' => false)),
      'visible'          => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('collections_fields_visibilities[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectionsFieldsVisibilities';
  }

}
