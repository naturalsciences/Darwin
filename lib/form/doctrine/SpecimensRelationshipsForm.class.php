<?php

/**
 * CatalogueRelationships form.
 *
 * @package    form
 * @subpackage CatalogueRelationships
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SpecimensRelationshipsForm extends BaseCatalogueRelationshipsForm
{
  public function configure()
  {
    $this->setDefault('table_name', 'specimens');
    unset($this['referenced_relation']);
    unset($this['record_id_1']);
    unset($this['relationship_type']);
    $this->widgetSchema['enabled'] = new sfWidgetFormInputCheckbox();
    /*$this->widgetSchema['relationship_type'] = new sfWidgetFormChoice(array(
        'choices'  => $this->getRelationsTypes(),
    ));*/
    $this->validatorSchema['enabled'] = new sfValidatorChoice(array(
        'choices' => array('on','off'),
        'required' => false,
        ));

    $this->validatorSchema['record_id_2'] = new sfValidatorInteger(array('required' => false,));

    $this->widgetSchema['record_id_2'] = new widgetFormButtonRef(array(
       'model' => 'Taxonomy',
       'link_url' => 'taxonomy/choose',
       'method' => 'getName',
       'nullable' => true,
       'box_title' => $this->getI18N()->__('Choose Taxon'),
     ));
    $this->widgetSchema['record_id_2']->setLabel(' ');
  }

  public function save($con = null)
  {
    if($this->getValue('record_id_2') != null)
      return parent::save($con);
    return null;
  }

  public function getRelationsTypes()
  {
    return array(
	'recombined from',
	'current taxon',
    );
  }
}