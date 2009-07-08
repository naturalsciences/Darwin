<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * VernacularNames filter form base class.
 *
 * @package    filters
 * @subpackage VernacularNames *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseVernacularNamesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                       => new sfWidgetFormFilterInput(),
      'name_ts'                    => new sfWidgetFormFilterInput(),
      'country_language_full_text' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'                       => new sfValidatorPass(array('required' => false)),
      'name_ts'                    => new sfValidatorPass(array('required' => false)),
      'country_language_full_text' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('vernacular_names_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'VernacularNames';
  }

  public function getFields()
  {
    return array(
      'vernacular_class_ref'       => 'Number',
      'name'                       => 'Text',
      'name_ts'                    => 'Text',
      'country_language_full_text' => 'Text',
    );
  }
}