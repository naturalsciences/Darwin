<?php

/**
 * VernacularNames filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseVernacularNamesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                       => new sfWidgetFormFilterInput(array('with_empty' => false)),
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

    $this->setupInheritance();

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
