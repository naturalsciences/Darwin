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
      ),
      'individual' => array(
      ),
      'part' => array(
      ),
    );
  }

  public function doMassAction()
  {
    if($this->isBound() && $this->isValid())
    {
      $this->getEmbeddedForm('MassActionForm')->doMassAction($this->getValue('item_list'), $this->getValue('MassActionForm'));
    }
  }

  public function setSubForm($form_name)
  {
    $subForm = new $form_name();
    $this->embedForm('MassActionForm',$subForm);
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
    parent::bind($taintedValues,$taintedFiles);
  }

  public function configure()
  {
    $action_sources = self::getActionsSources();
    $action_possibles = self::getPossibleActions();

    sfWidgetFormSchema::setDefaultFormFormatterName('list');
    $this->widgetSchema->setNameFormat('mass_action[%s]');

    $this->widgetSchema['source'] = new sfWidgetFormChoice(array( 
     'choices' => $action_sources
    ));
    $this->validatorSchema['source'] = new sfValidatorPass();

    $this->widgetSchema['field_action'] = new sfWidgetFormChoice(array( 
     'choices' =>  array()
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
