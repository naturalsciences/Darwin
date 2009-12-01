<?php

/**
 * Taxonomy form.
 *
 * @package    form
 * @subpackage Taxonomy
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class TaxonomyForm extends BaseTaxonomyForm
{
  public function configure()
  {
    $this->widgetSchema['name'] = new sfWidgetFormInput();
    $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
        'choices'  => array($this->getI18N()->__('valid'), $this->getI18N()->__('invalid'), $this->getI18N()->__('depracated')),
    ));
    $this->widgetSchema['level_ref'] = new sfWidgetFormDoctrineChoice(array(
	'model' => 'CatalogueLevels',
	'table_method' => 'getLevelsForTaxonomy',
	'add_empty' => true
      ));
    $this->widgetSchema['parent_ref'] = new widgetFormButtonRef(array(
       'model' => 'Taxonomy',
       'method' => 'getName',
       'link_url' => 'taxonomy/choose',
       'box_title' => $this->getI18N()->__('Choose Parent'),
     ));
      $this->validatorSchema['status'] = new sfValidatorChoice(array('choices'  => array($this->getI18N()->__('valid'), $this->getI18N()->__('invalid'), $this->getI18N()->__('depracated')), 'required' => true));

  }
}