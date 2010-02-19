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
    $statuses = array('valid'=>$this->getI18N()->__('valid'), 'invalid'=>$this->getI18N()->__('invalid'), 'deprecated'=>$this->getI18N()->__('deprecated'));
    $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
        'choices'  => $statuses,
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
    $this->widgetSchema->setLabels(array('level_ref' => 'Level',
                                         'parent_ref' => 'Parent'
                                        )
                                  );
    $this->validatorSchema['status'] = new sfValidatorChoice(array('choices'  => array_keys($statuses), 'required' => true));

    $this->addKeywordsRelation('lithology');
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
}