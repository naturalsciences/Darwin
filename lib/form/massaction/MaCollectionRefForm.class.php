<?php

class MaCollectionRefForm extends BaseForm
{
  public function configure()
  {
    $this->widgetSchema['collection_ref'] = new widgetFormButtonRef(
      array(
        'model' => 'Collections',
        'link_url' => 'collection/choose',
        'method' => 'getName',
        'box_title' => $this->getI18N()->__('Choose Collection'),
        'button_class'=>'',
      )
    );
    $this->widgetSchema['collection_ref']->setLabel('Choose New Collection');
    $this->validatorSchema['collection_ref'] = new sfValidatorDoctrineChoice(array(
      'model' => 'Collections',
      'min' => 0,
      'required' => true
    ));

  }

  public function doMassAction($items, $values)
  {
    $new_collection = $values['collection_ref'];

    $q = Doctrine_Query::create()
    ->update('Specimens s')
    ->set('s.collection_ref', '?', $new_collection)
    ->whereIn('s.id ', $items);
    $updated = $q->execute();
    return $updated;
  }
}