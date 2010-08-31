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
    unset($this['path']);

    $this->widgetSchema['table'] = new sfWidgetFormInputHidden(array('default'=>'lithostratigraphy'));
    $this->widgetSchema['name'] = new sfWidgetFormInput();
    $this->widgetSchema['name']->setAttributes(array('class'=>'large_size'));
    $statuses = array('valid'=>$this->getI18N()->__('valid'), 'invalid'=>$this->getI18N()->__('invalid'), 'deprecated'=>$this->getI18N()->__('deprecated'));
    $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
        'choices'  => $statuses,
    ));
    $this->widgetSchema['level_ref'] = new sfWidgetFormDarwinDoctrineChoice(array(
        'model' => 'CatalogueLevels',
        'table_method' => array('method'=>'getLevelsByTypes', 'parameters'=>array(array('table'=>'lithostratigraphy'))),
        'add_empty' => true
      ),
      array('class'=>'catalogue_level')
      );
    $this->widgetSchema['parent_ref'] = new widgetFormButtonRef(array(
       'model' => 'Lithostratigraphy',
       'method' => 'getName',
       'link_url' => 'lithostratigraphy/choose',
       'box_title' => $this->getI18N()->__('Choose Parent'),
       'button_is_hidden' => true,
     ));
    $this->widgetSchema->setLabels(array('level_ref' => 'Level',
                                         'parent_ref' => 'Parent'
                                        )
                                  );
    $this->validatorSchema['status'] = new sfValidatorChoice(array('choices'  => array_keys($statuses), 'required' => true));
    $this->validatorSchema['table'] = new sfValidatorString(array('required' => false));

    $this->addKeywordsRelation('lithostratigraphy');
    $subForm = new sfForm();
    $this->embedForm('newVal',$subForm);
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    parent::bindKeywords($taintedValues,$taintedFiles);
  }

  public function saveEmbeddedForms($con = null, $forms = null)
  {
    parent::saveKeywordsEmbeddedForms($con, $forms);
    return parent::saveEmbeddedForms($con, $forms);
  }

  public function getJavascripts()
  {
    $javascripts = parent::getJavascripts();
    $javascripts[]='/js/catalogue_level_edit.js';
    return $javascripts;    
  }
}