<?php

/**
 * Comments form base class.
 *
 * @package    form
 * @subpackage comments
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCommentsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                         => new sfWidgetFormInputHidden(),
      'referenced_relation'        => new sfWidgetFormTextarea(),
      'record_id'                  => new sfWidgetFormInput(),
      'notion_concerned'           => new sfWidgetFormTextarea(),
      'comment'                    => new sfWidgetFormTextarea(),
      'comment_ts'                 => new sfWidgetFormTextarea(),
      'comment_language_full_text' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorDoctrineChoice(array('model' => 'Comments', 'column' => 'id', 'required' => false)),
      'referenced_relation'        => new sfValidatorString(),
      'record_id'                  => new sfValidatorInteger(),
      'notion_concerned'           => new sfValidatorString(),
      'comment'                    => new sfValidatorString(),
      'comment_ts'                 => new sfValidatorString(array('required' => false)),
      'comment_language_full_text' => new sfValidatorString(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('comments[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Comments';
  }

}
