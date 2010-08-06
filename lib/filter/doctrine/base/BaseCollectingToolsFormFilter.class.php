<?php

/**
 * CollectingTools filter form base class.
 *
 * @package    darwin
 * @subpackage filter
 * @author     DB team <collections@naturalsciences.be>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BaseCollectingToolsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'tool'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tool_indexed' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'tool'         => new sfValidatorPass(array('required' => false)),
      'tool_indexed' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('collecting_tools_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CollectingTools';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'tool'         => 'Text',
      'tool_indexed' => 'Text',
    );
  }
}
