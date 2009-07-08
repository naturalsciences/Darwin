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
      'table_name'                 => new sfWidgetFormTextarea(),
      'record_id'                  => new sfWidgetFormInput(),
      'notion_concerned'           => new sfWidgetFormTextarea(),
      'comment'                    => new sfWidgetFormTextarea(),
      'comment_ts'                 => new sfWidgetFormTextarea(),
      'comment_language_full_text' => new sfWidgetFormTextarea(),
    ));

    $this->setValidators(array(
      'id'                         => new sfValidatorDoctrineChoice(array('model' => 'Comments', 'column' => 'id', 'required' => false)),
      'table_name'                 => new sfValidatorString(array('max_length' => 2147483647)),
      'record_id'                  => new sfValidatorInteger(),
      'notion_concerned'           => new sfValidatorString(array('max_length' => 2147483647)),
      'comment'                    => new sfValidatorString(array('max_length' => 2147483647)),
      'comment_ts'                 => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
      'comment_language_full_text' => new sfValidatorString(array('max_length' => 2147483647, 'required' => false)),
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
