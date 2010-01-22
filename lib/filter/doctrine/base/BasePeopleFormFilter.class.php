<?php

/**
 * People filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePeopleFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'is_physical'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'sub_type'              => new sfWidgetFormFilterInput(),
      'public_class'          => new sfWidgetFormChoice(array('choices' => array('' => '', 'public' => 'public', 'private' => 'private'))),
      'formated_name'         => new sfWidgetFormFilterInput(),
      'formated_name_indexed' => new sfWidgetFormFilterInput(),
      'formated_name_ts'      => new sfWidgetFormFilterInput(),
      'title'                 => new sfWidgetFormFilterInput(),
      'family_name'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'given_name'            => new sfWidgetFormFilterInput(),
      'additional_names'      => new sfWidgetFormFilterInput(),
      'birth_date_mask'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'birth_date'            => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'gender'                => new sfWidgetFormChoice(array('choices' => array('' => '', 'M' => 'M', 'F' => 'F'))),
      'db_people_type'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'end_date_mask'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'end_date'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'is_physical'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'sub_type'              => new sfValidatorPass(array('required' => false)),
      'public_class'          => new sfValidatorChoice(array('required' => false, 'choices' => array('public' => 'public', 'private' => 'private'))),
      'formated_name'         => new sfValidatorPass(array('required' => false)),
      'formated_name_indexed' => new sfValidatorPass(array('required' => false)),
      'formated_name_ts'      => new sfValidatorPass(array('required' => false)),
      'title'                 => new sfValidatorPass(array('required' => false)),
      'family_name'           => new sfValidatorPass(array('required' => false)),
      'given_name'            => new sfValidatorPass(array('required' => false)),
      'additional_names'      => new sfValidatorPass(array('required' => false)),
      'birth_date_mask'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'birth_date'            => new sfValidatorPass(array('required' => false)),
      'gender'                => new sfValidatorChoice(array('required' => false, 'choices' => array('M' => 'M', 'F' => 'F'))),
      'db_people_type'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end_date_mask'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'end_date'              => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('people_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'People';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'is_physical'           => 'Boolean',
      'sub_type'              => 'Text',
      'public_class'          => 'Enum',
      'formated_name'         => 'Text',
      'formated_name_indexed' => 'Text',
      'formated_name_ts'      => 'Text',
      'title'                 => 'Text',
      'family_name'           => 'Text',
      'given_name'            => 'Text',
      'additional_names'      => 'Text',
      'birth_date_mask'       => 'Number',
      'birth_date'            => 'Text',
      'gender'                => 'Enum',
      'db_people_type'        => 'Number',
      'end_date_mask'         => 'Number',
      'end_date'              => 'Text',
    );
  }
}
