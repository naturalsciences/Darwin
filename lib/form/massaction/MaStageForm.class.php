<?php

class MaStageForm extends BaseForm
{
  public function configure()
  {
    $this->widgetSchema['stage'] = new widgetFormSelectComplete(array(
      'model' => 'SpecimenIndividuals',
      'table_method' => 'getDistinctStages',
      'method' => 'getStage',
      'key_method' => 'getStage',
      'add_empty' => true,
      'change_label' => 'Pick a stage in the list',
      'add_label' => 'Add another stage',
    ));

    $this->widgetSchema['stage']->setLabel('Choose New Stage');
    $this->validatorSchema['stage'] = new sfValidatorString(array('required' => false));

  }

  public function doGroupedAction($query,$values, $items)
  {
    $new_taxon = $values['stage'];
    $query->set('s.stage', '?', $new_taxon);
    return $query;
  }

}