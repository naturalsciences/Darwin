<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * CatalogueProperties filter form base class.
 *
 * @package    filters
 * @subpackage CatalogueProperties *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseCataloguePropertiesFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'table_name'                 => new sfWidgetFormFilterInput(),
      'record_id'                  => new sfWidgetFormFilterInput(),
      'property_type'              => new sfWidgetFormFilterInput(),
      'property_sub_type'          => new sfWidgetFormFilterInput(),
      'property_sub_type_indexed'  => new sfWidgetFormFilterInput(),
      'property_qualifier'         => new sfWidgetFormFilterInput(),
      'property_qualifier_indexed' => new sfWidgetFormFilterInput(),
      'date_from_mask'             => new sfWidgetFormFilterInput(),
      'date_from timestamp'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'date_to_mask'               => new sfWidgetFormFilterInput(),
      'date_to timestamp'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'property_unit'              => new sfWidgetFormFilterInput(),
      'property_accuracy_unit'     => new sfWidgetFormFilterInput(),
      'property_method'            => new sfWidgetFormFilterInput(),
      'property_method_indexed'    => new sfWidgetFormFilterInput(),
      'property_tool'              => new sfWidgetFormFilterInput(),
      'property_tool_indexed'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'table_name'                 => new sfValidatorPass(array('required' => false)),
      'record_id'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'property_type'              => new sfValidatorPass(array('required' => false)),
      'property_sub_type'          => new sfValidatorPass(array('required' => false)),
      'property_sub_type_indexed'  => new sfValidatorPass(array('required' => false)),
      'property_qualifier'         => new sfValidatorPass(array('required' => false)),
      'property_qualifier_indexed' => new sfValidatorPass(array('required' => false)),
      'date_from_mask'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'date_from timestamp'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'date_to_mask'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'date_to timestamp'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'property_unit'              => new sfValidatorPass(array('required' => false)),
      'property_accuracy_unit'     => new sfValidatorPass(array('required' => false)),
      'property_method'            => new sfValidatorPass(array('required' => false)),
      'property_method_indexed'    => new sfValidatorPass(array('required' => false)),
      'property_tool'              => new sfValidatorPass(array('required' => false)),
      'property_tool_indexed'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('catalogue_properties_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CatalogueProperties';
  }

  public function getFields()
  {
    return array(
      'id'                         => 'Number',
      'table_name'                 => 'Text',
      'record_id'                  => 'Number',
      'property_type'              => 'Text',
      'property_sub_type'          => 'Text',
      'property_sub_type_indexed'  => 'Text',
      'property_qualifier'         => 'Text',
      'property_qualifier_indexed' => 'Text',
      'date_from_mask'             => 'Number',
      'date_from timestamp'        => 'Date',
      'date_to_mask'               => 'Number',
      'date_to timestamp'          => 'Date',
      'property_unit'              => 'Text',
      'property_accuracy_unit'     => 'Text',
      'property_method'            => 'Text',
      'property_method_indexed'    => 'Text',
      'property_tool'              => 'Text',
      'property_tool_indexed'      => 'Text',
    );
  }
}