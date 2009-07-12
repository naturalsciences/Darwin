<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * MyPreferences filter form base class.
 *
 * @package    filters
 * @subpackage MyPreferences *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseMyPreferencesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_by'    => new sfWidgetFormFilterInput(),
      'col_num'     => new sfWidgetFormFilterInput(),
      'mandatory'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'visible'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'opened'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'color'       => new sfWidgetFormFilterInput(),
      'icon_ref'    => new sfWidgetFormDoctrineChoice(array('model' => 'Multimedia', 'add_empty' => true)),
      'title_perso' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'order_by'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'col_num'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mandatory'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'visible'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'opened'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'color'       => new sfValidatorPass(array('required' => false)),
      'icon_ref'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Multimedia', 'column' => 'id')),
      'title_perso' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('my_preferences_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'MyPreferences';
  }

  public function getFields()
  {
    return array(
      'user_ref'    => 'Number',
      'category'    => 'Text',
      'group_name'  => 'Text',
      'order_by'    => 'Number',
      'col_num'     => 'Number',
      'mandatory'   => 'Boolean',
      'visible'     => 'Boolean',
      'opened'      => 'Boolean',
      'color'       => 'Text',
      'icon_ref'    => 'ForeignKey',
      'title_perso' => 'Text',
    );
  }
}