<?php

/**
 * CollectionsAdmin form base class.
 *
 * @method CollectionsAdmin getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCollectionsAdminForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'collection_ref' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Collections'), 'add_empty' => false)),
      'user_ref'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'collection_ref' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Collections'), 'required' => false)),
      'user_ref'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Users'), 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('collections_admin[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectionsAdmin';
  }

}
