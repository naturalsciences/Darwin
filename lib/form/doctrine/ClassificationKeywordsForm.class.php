<?php

/**
 * ClassificationKeywords form.
 *
 * @package    form
 * @subpackage ClassificationKeywords
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 6174 2007-11-27 06:22:40Z fabien $
 */
class ClassificationKeywordsForm extends BaseClassificationKeywordsForm
{
  public function configure()
  {
    $this->widgetSchema['referenced_relation'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['record_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['keyword_type'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['keyword'] = new sfWidgetFormInputHidden();

    if(isset($this->options['new_object']))
    {
      $this->widgetSchema->setNameFormat('classsification_keywords[new]['.$this->options['num'].'][%s]');
    }
    else
    {
      $this->widgetSchema->setNameFormat('classsification_keywords[old]['.$this->getObject()->getId().'][%s]');
    }
  }
}