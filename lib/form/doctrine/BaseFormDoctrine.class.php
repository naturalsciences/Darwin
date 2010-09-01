<?php

/**
 * Project form base class.
 *
 * @package    form
 * @version    SVN: $Id: sfDoctrineFormBaseTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
abstract class BaseFormDoctrine extends sfFormDoctrine
{
  public function setup()
  {
    sfWidgetFormSchema::setDefaultFormFormatterName('list');
  }

  protected function getI18N()
  {
     return sfContext::getInstance()->getI18N();
  }

  public function getCurrentCulture()
  {
    return sfContext::getInstance()->getUser()->getCulture();
  }

  /**
  * Bind
  * @see getFieldsByGroup
  */
  public function bind(array $taintedValues = null, array $taintedFiles = null)
  {
    $fields_groups = $this->getFieldsByGroup();
    foreach($fields_groups as $group)
    {
      foreach($group as $field)
      {
        if(!isset($taintedValues[$field]) && get_class($this->widgetSchema[$field]) != "sfWidgetFormInputCheckbox")
        {
          foreach($group as $ufield)
          {
            $this->offsetUnset($ufield);
          }
          break;
        }
      }
    }
    parent::bind($taintedValues, $taintedFiles);
  }

  /**
  * Get fields by widget
  * In the bind function if 1 field (or more) of the group is not sended in tainted values,
  * we remove the whole group from the form
  * @return array of widget name with array of widgets
  * @see bind
  */
  protected function getFieldsByGroup()
  {
    return array();
  }

  public function addKeywordsRelation($table)
  {
    $subForm = new sfForm();
    $keywords = Doctrine::getTable('ClassificationKeywords')->findForTable($table, $this->getObject()->getId());
    foreach ($keywords as $index => $childObject)
    {
      $form = new ClassificationKeywordsForm($childObject);
      $subForm->embedForm($index, $form);
      $subForm->getWidgetSchema()->setLabel($index, (string) $childObject);
    }
    $this->embedForm('ClassificationKeywords', $subForm);
  }

  
  public function addKeyword($num, $type="", $key="")
  {
    $val = new ClassificationKeywords();
    $val->setReferencedRelation( sfInflector::tableize($this->getModelName()) );
    $val->setRecordId($this->getObject());
    $val->setKeywordType($type);
    $val->setKeyword($key);

    $form = new ClassificationKeywordsForm($val);
  
    $this->embeddedForms['newVal']->embedForm($num, $form);
    //Re-embedding the container
    $this->embedForm('newVal', $this->embeddedForms['newVal']);
  }

  public function bindKeywords(array $taintedValues = null, array $taintedFiles = null)
  {
    if(isset($taintedValues['newVal']))
    {
      foreach($taintedValues['newVal'] as $key=>$newVal)
      {
        $taintedValues['newVal'][$key]['record_id'] = $this->getObject();
        if (!isset($this['newVal'][$key]))
        {
          $this->addKeyword($key);
        }
      }
    }
    parent::bind($taintedValues, $taintedFiles);
  }

  public function saveKeywordsEmbeddedForms($con = null, $forms = null)
  {
    if (null === $forms)
    {
      $value = $this->getValue('newVal');
      foreach($this->embeddedForms['newVal']->getEmbeddedForms() as $name => $form)
      {
        if (!isset($value[$name]['keyword_type']) || $value[$name]['keyword_type']=='' )
        {
          unset($this->embeddedForms['newVal'][$name]);
        }
      }

      $value = $this->getValue('ClassificationKeywords');
      foreach($this->embeddedForms['ClassificationKeywords']->getEmbeddedForms() as $name => $form)
      {
  
        if (!isset($value[$name]['keyword_type']) || $value[$name]['keyword_type']=='' )
        {
          $form->getObject()->delete();
          unset($this->embeddedForms['ClassificationKeywords'][$name]);
        }
      }
    }
  }
}
