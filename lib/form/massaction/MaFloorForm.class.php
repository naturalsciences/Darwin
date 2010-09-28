<?php

class MaFloorForm extends BaseForm
{
  public function configure()
  {
    $this->widgetSchema['floor'] = new widgetFormSelectComplete(array(
      'model' => 'SpecimenParts',
      'table_method' => 'getDistinctFloors',
      'method' => 'getFloors',
      'key_method' => 'getFloors',
      'add_empty' => true,
      'change_label' => 'Pick a floor in the list',
      'add_label' => 'Add another floor',
    ));

    $this->widgetSchema['floor']->setLabel('Choose New Floor');
    $this->validatorSchema['floor'] = new sfValidatorString(array('required' => false));

  }

  public function doGroupedAction($query,$values, $items)
  {
    $new_taxon = $values['floor'];
    $query->set('s.floor', '?', $new_taxon);
    return $query;
  }

}