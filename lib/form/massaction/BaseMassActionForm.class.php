<?php

class BaseMassActionForm extends sfFormSymfony
{
  protected static function getI18N()
  {
    return sfContext::getInstance()->getI18N();
  }

  public static function getActionsSources()
  {
    return array('' => '', 'specimen'=>'specimen','individual'=>'individual','part'=>'part');
  }
  
  public static function getPossibleActions()
  {
    return array(
      'specimen' => array(
        'collection_ref' => self::getI18N()->__('Collection'),
        'taxon_ref' => self::getI18N()->__('Taxonomy'),
      ),
     /* 'individual' => array(
      ),*/
      'part' => array(
        'maintenance' => self::getI18N()->__('Maintenance'),
      ),
    );
  }

  public function doMassAction()
  {
    if($this->isBound() && $this->isValid())
    {
      $actions_values = $this->getValue('MassActionForm');
      if($this->getValue('source') == 'specimen')
        $query = Doctrine_Query::create()->update('Specimens s');
      elseif($this->getValue('source') == 'individual')
        $query = Doctrine_Query::create()->update('SpecimenIndividuals s');
      else
        $query = Doctrine_Query::create()->update('SpecimenParts s');

      $query->whereIn('s.id ', $this->getValue('item_list'));
      $group_action = 0;
      foreach($this->embeddedForms['MassActionForm'] as $key=> $form)
      {
        if (method_exists($this->getEmbeddedForm('MassActionForm')->getEmbeddedForm($key), 'doGroupedAction'))
        {
          $this->getEmbeddedForm('MassActionForm')->getEmbeddedForm($key)->doGroupedAction($query, $actions_values[$key], $this->getValue('item_list'));
          $group_action++;
        }

        if (method_exists($this->getEmbeddedForm('MassActionForm')->getEmbeddedForm($key), 'doMassAction'))
        {
          $this->getEmbeddedForm('MassActionForm')->getEmbeddedForm($key)->doMassAction($this->getValue('item_list'), $actions_values[$key]);
        }
      }
      if($group_action)
        $query->execute();
    }
  }

  public function addSubForm($field_name)
  {
    $form_name = $this->getFormNameForAction($field_name);
    $subForm = new $form_name();

    $this->embeddedForms['MassActionForm']->embedForm($field_name, $subForm);
      //Re-embedding the container
    $this->embedForm('MassActionForm', $this->embeddedForms['MassActionForm']);
  }

  protected function getFormNameForAction($action)
  {
    if($action == 'collection_ref')
      return 'MaCollectionRefForm';
    elseif($action == 'taxon_ref')
      return 'MaTaxonomyRefForm';
    else
      return 'sfForm';
  }

  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    if(isset($taintedValues['source']) && in_array($taintedValues['source'], array('specimen','individual','part')))
    {
      if($taintedValues['source'] == 'specimen')
        $model = 'Specimens';
      elseif($taintedValues['source'] == 'individual')
        $model = 'SpecimenIndividuals';
      else
        $model = 'SpecimenParts';

      $this->validatorSchema['item_list'] = new sfValidatorDoctrineChoice(array(
        'multiple' => true,
        'model' => $model,
        'min' => 1,
      ));
    }

    if(isset($taintedValues['field_action']) && is_array(($taintedValues['field_action'])) && count($taintedValues['field_action']) != 0 
      && isset($taintedValues['MassActionForm']) && is_array(($taintedValues['MassActionForm'])) && count($taintedValues['MassActionForm']) != 0 )
    {
      foreach($taintedValues['field_action'] as $form_name)
      {
          $this->addSubForm($form_name);
      }
    }
    parent::bind($taintedValues,$taintedFiles);
  }

  public function configure()
  {
    $action_sources = self::getActionsSources();

    sfWidgetFormSchema::setDefaultFormFormatterName('list');
    $this->widgetSchema->setNameFormat('mass_action[%s]');

    $this->widgetSchema['source'] = new sfWidgetFormChoice(array( 
     'choices' => $action_sources
    ));
    $this->validatorSchema['source'] = new sfValidatorPass();

    $this->widgetSchema['field_action'] = new sfWidgetFormSelectCheckbox(array( 
     'choices' =>  self::getPossibleActions(),
     'template' => '<div class="group_%group% fld_group"><label>%group%</label> %options%</div>',
    ));

    $this->validatorSchema['field_action'] = new sfValidatorPass();

    $this->widgetSchema['item_list'] = new sfWidgetFormChoice(array( 'choices' => array() ));

    $this->validatorSchema['item_list'] = new sfValidatorDoctrineChoice(array(
      'multiple' => true,
      'model' => 'Specimens',
      'min' => 0,
    ));

    $subForm = new sfForm();
    $this->embedForm('MassActionForm',$subForm);
  }
}
