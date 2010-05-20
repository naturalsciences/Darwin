<?php

/**
 * SpecimenParts form.
 *
 * @package    form
 * @subpackage SpecimenParts
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SpecimenPartsForm extends BaseSpecimenPartsForm
{
  public function configure()
  {
	unset( $this['specimen_individual_ref'] , $this['id']);

	//Widget to see if the record is there
// 	$this->widgetSchema['is_available'] = new sfWidgetFormInputHidden();
// 	$this->widgetSchema['is_available']->setDefault('1');
// 	$this->validatorSchema['is_available'] = new sfValidatorPass();


	$this->widgetSchema['specimen_part'] = new widgetFormSelectComplete(array(
	  'model' => 'SpecimenParts',
	  'table_method' => 'getDistinctParts',
	  'method' => 'getParts',
	  'key_method' => 'getParts',
	  'add_empty' => true,
	  'change_label' => 'Pick parts in the list',
	  'add_label' => 'Add another parts',
    ));

	$this->widgetSchema['building'] = new widgetFormSelectComplete(array(
	  'model' => 'SpecimenParts',
	  'table_method' => 'getDistinctBuildings',
	  'method' => 'getBuildings',
	  'key_method' => 'getBuildings',
	  'add_empty' => true,
	  'change_label' => 'Pick a building in the list',
	  'add_label' => 'Add another building',
    ));

	$this->widgetSchema['floor'] = new widgetFormSelectComplete(array(
	  'model' => 'SpecimenParts',
	  'table_method' => 'getDistinctFloors',
	  'method' => 'getFloors',
	  'key_method' => 'getFloors',
	  'add_empty' => true,
	  'change_label' => 'Pick a floor in the list',
	  'add_label' => 'Add another floor',
    ));

	$this->widgetSchema['row'] = new widgetFormSelectComplete(array(
	  'model' => 'SpecimenParts',
	  'table_method' => 'getDistinctRows',
	  'method' => 'getRows',
	  'key_method' => 'getRows',
	  'add_empty' => true,
	  'change_label' => 'Pick a row in the list',
	  'add_label' => 'Add another row',
    ));

	$this->widgetSchema['room'] = new widgetFormSelectComplete(array(
	  'model' => 'SpecimenParts',
	  'table_method' => 'getDistinctRooms',
	  'method' => 'getRooms',
	  'key_method' => 'getRooms',
	  'add_empty' => true,
	  'change_label' => 'Pick a room in the list',
	  'add_label' => 'Add another room',
    ));

	$this->widgetSchema['shelf'] = new widgetFormSelectComplete(array(
	  'model' => 'SpecimenParts',
	  'table_method' => 'getDistinctShelfs',
	  'method' => 'getShelfs',
	  'key_method' => 'getShelfs',
	  'add_empty' => true,
	  'change_label' => 'Pick a shelf in the list',
	  'add_label' => 'Add another shelf',
    ));

	$this->widgetSchema['container_type'] = new widgetFormSelectComplete(array(
	  'model' => 'SpecimenParts',
	  'table_method' => 'getDistinctContainerTypes',
	  'method' => 'getContainerTypes',
	  'key_method' => 'getContainerTypes',
	  'add_empty' => true,
	  'change_label' => 'Pick a container in the list',
	  'add_label' => 'Add another container',
    ));

	$this->widgetSchema['sub_container_type'] = new widgetFormSelectComplete(array(
	  'model' => 'SpecimenParts',
	  'table_method' => 'getDistinctSubContainerTypes',
	  'method' => 'getSubContainerTypes',
	  'key_method' => 'getSubContainerTypes',
	  'add_empty' => true,
	  'change_label' => 'Pick a sub container type in the list',
	  'add_label' => 'Add another sub container type',
    ));

	$this->widgetSchema['specimen_status'] = new widgetFormSelectComplete(array(
	  'model' => 'SpecimenParts',
	  'table_method' => 'getDistinctStatus',
	  'method' => 'getStatus',
	  'key_method' => 'getStatus',
	  'add_empty' => true,
	  'change_label' => 'Pick a status in the list',
	  'add_label' => 'Add another status',
    ));

	$this->widgetSchema['container'] = new sfWidgetFormInput();
	$this->widgetSchema['sub_container'] = new sfWidgetFormInput();


	$this->widgetSchema['container_storage'] = new widgetFormSelectComplete(array(
	  'model' => 'SpecimenParts',
	  'change_label' => 'Pick a container storage in the list',
	  'add_label' => 'Add another container storage',
    ));

	$this->widgetSchema['sub_container_storage'] = new widgetFormSelectComplete(array(
	  'model' => 'SpecimenParts',
	  'change_label' => 'Pick a sub container storage in the list',
	  'add_label' => 'Add another sub container storage',
    ));


    $this->widgetSchema['container_storage']->setOption('forced_choices',
	  Doctrine::getTable('SpecimenParts')->getDistinctContainerStorages($this->getObject()->getContainerType())
	);

    $this->widgetSchema['sub_container_storage']->setOption('forced_choices',
	  Doctrine::getTable('SpecimenParts')->getDistinctSubContainerStorages($this->getObject()->getSubContainerType())
	);

	$this->widgetSchema['category'] = new sfWidgetFormChoice(array(
	  'choices' => SpecimenParts::getCategories(),
	));


    $this->mergePostValidator(new PartsValidatorSchema());
  }
}