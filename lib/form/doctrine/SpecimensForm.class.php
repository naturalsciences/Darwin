<?php

/**
 * Specimens form.
 *
 * @package    form
 * @subpackage Specimens
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class SpecimensForm extends BaseSpecimensForm
{
  public function configure()
  {
    $this->setDefaults(array(
        'gtu_ref' => 0,
        'expedition_ref' => 0,
    ));
    $this->widgetSchema->setNameFormat('specimen[%s]');
    $this->widgetSchema['collection_ref']->setOption('add_empty', true);
    $this->widgetSchema['collection_ref']->setOption('add_empty', true);
    $this->widgetSchema['acquisition_category'] = new sfWidgetFormDoctrineChoice(array(
      'model' => 'Specimens',
      'table_method' => 'getAcquisitionsCategories',
      'method' => 'getAcquisitionCategory',
      'add_empty' => true,
    ));

    $this->widgetSchema['accuracy'] = new sfWidgetFormChoice(array(
        'choices'  => array('exact','imprecise'),
        'expanded' => true,
    ));
    $this->setDefault('accuracy', 0);
  }
}