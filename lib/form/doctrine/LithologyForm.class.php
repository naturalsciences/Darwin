<?php

/**
 * Lithology form.
 *
 * @package    form
 * @subpackage Lithology
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class LithologyForm extends BaseLithologyForm
{
  public function configure()
  {
    $this->widgetSchema['name'] = new sfWidgetFormInput();
    $this->widgetSchema['name']->setAttributes(array('class'=>'large_size'));
    $statusKeys = array('valid', 'invalid', 'deprecated');
    $statusVals = array($this->getI18N()->__('valid'), $this->getI18N()->__('invalid'), $this->getI18N()->__('deprecated'));
    $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
        'choices'  => array_combine($statusKeys,$statusVals),
    ));
    $this->widgetSchema['level_ref'] = new sfWidgetFormDoctrineChoice(array(
	'model' => 'CatalogueLevels',
	'table_method' => 'getLevelsForLithology',
	'add_empty' => true
      ));
    $this->widgetSchema['parent_ref'] = new widgetFormButtonRef(array(
       'model' => 'Lithology',
       'method' => 'getName',
       'link_url' => 'lithology/choose',
       'box_title' => $this->getI18N()->__('Choose Parent'),
     ));
      $this->validatorSchema['status'] = new sfValidatorChoice(array('choices'  => array_combine($statusKeys,$statusVals), 'required' => true));

  }
}