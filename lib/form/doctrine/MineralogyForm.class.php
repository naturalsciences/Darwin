<?php

/**
 * Mineralogy form.
 *
 * @package    form
 * @subpackage Mineralogy
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class MineralogyForm extends BaseMineralogyForm
{
  public function configure()
  {
    $this->widgetSchema['code'] = new sfWidgetFormInput();
    $this->widgetSchema['name'] = new sfWidgetFormInput();
    $this->widgetSchema['formule'] = new sfWidgetFormInput();

    $this->widgetSchema['code']->setAttributes(array('class'=>'small_size'));
    $this->widgetSchema['name']->setAttributes(array('class'=>'large_size'));
    $this->widgetSchema['formule']->setAttributes(array('class'=>'medium_size'));

    $statuses = array('valid'=>$this->getI18N()->__('valid'), 'invalid'=>$this->getI18N()->__('invalid'), 'deprecated'=>$this->getI18N()->__('deprecated'));
    $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
        'choices'  => $statuses,
    ));

    $classifications = array('strunz'=>'Strunz', 'dana'=>'Dana');
    $this->widgetSchema['classification'] = new sfWidgetFormChoice(array(
        'choices'  => $classifications,
    ));

    $this->widgetSchema['level_ref'] = new sfWidgetFormDoctrineChoice(array(
	'model' => 'CatalogueLevels',
	'table_method' => 'getLevelsForMineralogy',
	'add_empty' => true
      ));

    $this->widgetSchema['parent_ref'] = new widgetFormButtonRef(array(
       'model' => 'Mineralogy',
       'method' => 'getName',
       'link_url' => 'mineralogy/choose',
       'box_title' => $this->getI18N()->__('Choose Parent'),
     ));

    $this->widgetSchema['cristal_system'] = new widgetFormSelectComplete(array('model' => 'Mineralogy',
                                                                               'table_method' => 'getDistinctSystems',
                                                                               'method' => 'getCSystem',
                                                                               'key_method' => 'getCSystem',
                                                                               'add_empty' => false,
                                                                               'change_label' => 'Pick a system in the list',
                                                                               'add_label' => 'Add another system',
                                                                              )
                                                                        );

    $this->widgetSchema->setLabels(array('cristal_system' => 'Cristalographic system',
                                         'level_ref' => 'Level',
                                         'parent_ref' => 'Parent'
                                        )
                                  );

    $this->validatorSchema['status'] = new sfValidatorChoice(array('choices'  => array_keys($statuses), 'required' => true));
    $this->validatorSchema['classification'] = new sfValidatorChoice(array('choices'  => array_keys($classifications), 'required' => true));

    $this->addKeywordsRelation('mineralogy');
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