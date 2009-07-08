<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Comments filter form base class.
 *
 * @package    filters
 * @subpackage Comments *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCommentsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name'                 => new sfWidgetFormFilterInput(),
      'record_id'                  => new sfWidgetFormFilterInput(),
      'notion_concerned'           => new sfWidgetFormFilterInput(),
      'comment'                    => new sfWidgetFormFilterInput(),
      'comment_ts'                 => new sfWidgetFormFilterInput(),
      'comment_language_full_text' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'table_name'                 => new sfValidatorPass(array('required' => false)),
      'record_id'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'notion_concerned'           => new sfValidatorPass(array('required' => false)),
      'comment'                    => new sfValidatorPass(array('required' => false)),
      'comment_ts'                 => new sfValidatorPass(array('required' => false)),
      'comment_language_full_text' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('comments_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Comments';
  }

  public function getFields()
  {
    return array(
      'id'                         => 'Number',
      'table_name'                 => 'Text',
      'record_id'                  => 'Number',
      'notion_concerned'           => 'Text',
      'comment'                    => 'Text',
      'comment_ts'                 => 'Text',
      'comment_language_full_text' => 'Text',
    );
  }
}