<?php

/**
 * CataloguePeople form base class.
 *
 * @package    form
 * @subpackage catalogue_people
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseCataloguePeopleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'table_name'      => new sfWidgetFormTextarea(),
      'record_id'       => new sfWidgetFormInput(),
      'people_type'     => new sfWidgetFormTextarea(),
      'people_sub_type' => new sfWidgetFormTextarea(),
      'order_by'        => new sfWidgetFormInput(),
      'people_ref'      => new sfWidgetFormDoctrineChoice(array('model' => 'People', 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => 'CataloguePeople', 'column' => 'id', 'required' => false)),
      'table_name'      => new sfValidatorString(array('max_length' => 2147483647)),
      'record_id'       => new sfValidatorInteger(),
      'people_type'     => new sfValidatorString(array('max_length' => 2147483647)),
      'people_sub_type' => new sfValidatorString(array('max_length' => 2147483647)),
      'order_by'        => new sfValidatorInteger(),
      'people_ref'      => new sfValidatorDoctrineChoice(array('model' => 'People')),
    ));

    $this->widgetSchema->setNameFormat('catalogue_people[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CataloguePeople';
  }

}
