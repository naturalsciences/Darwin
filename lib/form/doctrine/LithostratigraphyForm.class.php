<?php

/**
 * Lithostratigraphy form.
 *
 * @package    form
 * @subpackage Lithostratigraphy
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class LithostratigraphyForm extends BaseLithostratigraphyForm
{
  public function configure()
  {
    $this->widgetSchema['name'] = new sfWidgetFormInput();
    $this->widgetSchema['name']->setAttributes(array('class'=>'large_size'));
    $statuses = array('valid'=>$this->getI18N()->__('valid'), 'invalid'=>$this->getI18N()->__('invalid'), 'deprecated'=>$this->getI18N()->__('deprecated'));
    $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
        'choices'  => $statuses,
    ));
    $this->widgetSchema['level_ref'] = new sfWidgetFormDoctrineChoice(array(
	'model' => 'CatalogueLevels',
	'table_method' => 'getLevelsForLithostratigraphy',
	'add_empty' => true
      ));
    $this->widgetSchema['parent_ref'] = new widgetFormButtonRef(array(
       'model' => 'Lithostratigraphy',
       'method' => 'getName',
       'link_url' => 'lithostratigraphy/choose',
       'box_title' => $this->getI18N()->__('Choose Parent'),
     ));
      $this->validatorSchema['status'] = new sfValidatorChoice(array('choices'  => array_keys($statuses), 'required' => true));

  }
}