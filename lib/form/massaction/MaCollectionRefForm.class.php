<?php

class MaCollectionRefForm extends BaseForm
{
  public function configure()
  {
    $this->widgetSchema['collection_ref'] = new widgetFormButtonRef(
      array('model' => 'Collections',
            'link_url' => 'collection/choose',
            'method' => 'getName',
            'box_title' => $this->getI18N()->__('Choose Collection'),
            'button_class'=>'',
           )
     );
    $this->widgetSchema['collection_ref']->setLabel('Choose New Collection');
    $this->validatorSchema['collection_ref'] = new sfValidatorInteger(array('required'=>true));

  }
}