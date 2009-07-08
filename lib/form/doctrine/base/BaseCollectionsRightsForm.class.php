<?php

/**
 * CollectionsRights form base class.
 *
 * @package    form
 * @subpackage collections_rights
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCollectionsRightsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'collection_ref' => new sfWidgetFormDoctrineChoice(array('model' => 'Collections', 'add_empty' => false)),
      'user_ref'       => new sfWidgetFormDoctrineChoice(array('model' => 'Users', 'add_empty' => false)),
      'rights'         => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorDoctrineChoice(array('model' => 'CollectionsRights', 'column' => 'id', 'required' => false)),
      'collection_ref' => new sfValidatorDoctrineChoice(array('model' => 'Collections')),
      'user_ref'       => new sfValidatorDoctrineChoice(array('model' => 'Users')),
      'rights'         => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('collections_rights[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectionsRights';
  }

}
