<?php

/**
 * GtuTags form base class.
 *
 * @method GtuTags getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseGtuTagsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tag_group_ref' => new sfWidgetFormInputHidden(),
      'gtu_ref'       => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'tag_group_ref' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'tag_group_ref', 'required' => false)),
      'gtu_ref'       => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'gtu_ref', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('gtu_tags[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'GtuTags';
  }

}
