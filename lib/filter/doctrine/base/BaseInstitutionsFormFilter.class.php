<?php

/**
 * Institutions filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseInstitutionsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'is_physical'           => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'sub_type'              => new sfWidgetFormFilterInput(),
      'formated_name'         => new sfWidgetFormFilterInput(),
      'formated_name_indexed' => new sfWidgetFormFilterInput(),
      'formated_name_ts'      => new sfWidgetFormFilterInput(),
      'family_name'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'additional_names'      => new sfWidgetFormFilterInput(),
      'db_people_type'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'is_physical'           => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'sub_type'              => new sfValidatorPass(array('required' => false)),
      'formated_name'         => new sfValidatorPass(array('required' => false)),
      'formated_name_indexed' => new sfValidatorPass(array('required' => false)),
      'formated_name_ts'      => new sfValidatorPass(array('required' => false)),
      'family_name'           => new sfValidatorPass(array('required' => false)),
      'additional_names'      => new sfValidatorPass(array('required' => false)),
      'db_people_type'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('institutions_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Institutions';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'is_physical'           => 'Boolean',
      'sub_type'              => 'Text',
      'formated_name'         => 'Text',
      'formated_name_indexed' => 'Text',
      'formated_name_ts'      => 'Text',
      'family_name'           => 'Text',
      'additional_names'      => 'Text',
      'db_people_type'        => 'Number',
    );
  }
}
