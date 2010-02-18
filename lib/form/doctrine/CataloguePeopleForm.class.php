<?php

/**
 * CataloguePeople form.
 *
 * @package    form
 * @subpackage CataloguePeople
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class CataloguePeopleForm extends BaseCataloguePeopleForm
{
  public function configure()
  {
    $this->widgetSchema['referenced_relation'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['record_id'] = new sfWidgetFormInputHidden();    

    $this->widgetSchema['people_ref'] = new widgetFormButtonRef(
      array(
	'model' => 'People',
	'method' => 'getFormatedName',
	'link_url' => 'people/choose',
	'box_title' => $this->getI18N()->__('Choose People'),
	'nullable' => false,
	'button_is_hidden' => true,
      )
    );

    $this->widgetSchema['people_type'] = new sfWidgetFormChoice(array(
      'choices'        => array('authors' => $this->getI18N()->__('Author'),
      'experts' => $this->getI18N()->__('Expert') ),
    ));

    $this->validatorSchema['people_type'] = new sfValidatorChoice(array(
      'choices'        => array('authors','experts')
    ));

    $this->widgetSchema['people_sub_type'] = new widgetFormSelectComplete(array(
        'model' => 'CataloguePeople',
	'change_label' => 'Pick a type in the list',
	'add_label' => 'Add another type',
    ));

    if($this->getObject()->isNew() && $this->getObject()->getPeopleType()=="authors")
      $this->widgetSchema['people_sub_type']->setDefault('Main Author');
    else
      $this->widgetSchema['people_sub_type']->setDefault('General');
    $this->widgetSchema['people_sub_type']->setOption('forced_choices', Doctrine::getTable('CataloguePeople')->getDistinctSubType($this->getObject()->getPeopleType()) );


  }
}