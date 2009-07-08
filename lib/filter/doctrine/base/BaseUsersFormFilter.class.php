<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * Users filter form base class.
 *
 * @package    filters
 * @subpackage Users *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUsersFormFilter extends BaseFormFilterDoctrine
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
      'family_name'           => new sfWidgetFormFilterInput(),
      'given_name'            => new sfWidgetFormFilterInput(),
      'additional_names'      => new sfWidgetFormFilterInput(),
      'birth_date_mask'       => new sfWidgetFormFilterInput(),
      'birth_date'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'gender'                => new sfWidgetFormChoice(array('choices' => array('' => '', 'M' => 'M', 'F' => 'F'))),
      'db_user_type'          => new sfWidgetFormFilterInput(),
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
      'birth_date'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'gender'                => new sfValidatorChoice(array('required' => false, 'choices' => array('M' => 'M', 'F' => 'F'))),
      'db_user_type'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('users_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Users';
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
      'birth_date'            => 'Date',
      'gender'                => 'Enum',
      'db_user_type'          => 'Number',
    );
  }
}