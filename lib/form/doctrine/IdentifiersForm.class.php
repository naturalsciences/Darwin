<?php

/**
 * Identifiers form.
 *
 * @package    form
 * @subpackage Identifiers
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class IdentifiersForm extends BaseCataloguePeopleForm
{

  public function configure()
  {
    $this->widgetSchema['people_ref'] = new widgetFormJQueryDLookup(
      array(
	'model' => 'People',
	'method' => 'getFormatedName',
	'nullable' => false,
        'fieldsHidders' => array('identifiers_people_type', 
                                 'identifiers_people_sub_type',),
      ),
      array('class' => 'hidden',)
    );

    $this->widgetSchema['referenced_relation'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['referenced_relation'] = new sfValidatorString();
    $this->widgetSchema['record_id'] = new sfWidgetFormInputHidden();    
    $this->validatorSchema['record_id'] = new sfValidatorInteger();
    $this->widgetSchema['people_type'] = new sfWidgetFormInputHidden(array('default'=>'identifier'));
    $this->widgetSchema['people_sub_type'] = new sfWidgetFormInputHidden(array('default'=>'General'));
    $this->widgetSchema['order_by'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['order_by'] = new sfValidatorInteger();

    /*Identifiers post-validation to empty null values*/
    $this->mergePostValidator(new IdentifiersValidatorSchema());

  }

}