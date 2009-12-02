<?php

/**
 * Comments form base class.
 *
 * @method Comments getObject() Returns the current form's model object
 *
 * @package    darwin
 * @subpackage form
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCommentsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                         => new sfWidgetFormInputHidden(),
      'referenced_relation'        => new sfWidgetFormTextarea(),
      'record_id'                  => new sfWidgetFormInputText(),
      'notion_concerned'           => new sfWidgetFormTextarea(),
      'comment'                    => new sfWidgetFormTextarea(),
      'comment_ts'                 => new sfWidgetFormTextarea(),
      'comment_language_full_text' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'referenced_relation'        => new sfValidatorString(),
      'record_id'                  => new sfValidatorInteger(),
      'notion_concerned'           => new sfValidatorString(),
      'comment'                    => new sfValidatorString(),
      'comment_ts'                 => new sfValidatorString(array('required' => false)),
      'comment_language_full_text' => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('comments[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Comments';
  }

}
