<?php

/**
 * Tags filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTagsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tag'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'group_type'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sub_group_type' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'tag'            => new sfValidatorPass(array('required' => false)),
      'group_type'     => new sfValidatorPass(array('required' => false)),
      'sub_group_type' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('tags_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Tags';
  }

  public function getFields()
  {
    return array(
      'gtu_ref'        => 'Number',
      'group_ref'      => 'Number',
      'tag'            => 'Text',
      'group_type'     => 'Text',
      'sub_group_type' => 'Text',
      'tag_indexed'    => 'Text',
    );
  }
}
