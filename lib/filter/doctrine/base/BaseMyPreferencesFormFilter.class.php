<?php

/**
 * MyPreferences filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseMyPreferencesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'order_by'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'col_num'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'mandatory'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'visible'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_available' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'opened'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'color'        => new sfWidgetFormFilterInput(),
      'icon_ref'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Multimedia'), 'add_empty' => true)),
      'title_perso'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'order_by'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'col_num'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mandatory'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'visible'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_available' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'opened'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'color'        => new sfValidatorPass(array('required' => false)),
      'icon_ref'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Multimedia'), 'column' => 'id')),
      'title_perso'  => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('my_preferences_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'MyPreferences';
  }

  public function getFields()
  {
    return array(
      'user_ref'     => 'Number',
      'category'     => 'Text',
      'group_name'   => 'Text',
      'order_by'     => 'Number',
      'col_num'      => 'Number',
      'mandatory'    => 'Boolean',
      'visible'      => 'Boolean',
      'is_available' => 'Boolean',
      'opened'       => 'Boolean',
      'color'        => 'Text',
      'icon_ref'     => 'ForeignKey',
      'title_perso'  => 'Text',
    );
  }
}
