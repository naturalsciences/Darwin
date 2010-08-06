<?php

/**
 * CollectingMethods filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCollectingMethodsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'method'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'method_indexed' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'method'         => new sfValidatorPass(array('required' => false)),
      'method_indexed' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('collecting_methods_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectingMethods';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'method'         => 'Text',
      'method_indexed' => 'Text',
    );
  }
}
