<?php

class SearchExpeditionForm extends DarwinForm
{

  public function configure()
  {
    $years = range(1800, date('Y'));
    $this->setWidgets(array(
      'name' => new sfWidgetFormInput(),
      'date_from' => new sfWidgetFormJQueryFuzzyDate(array('culture' => $this->getCurrentCulture(), )),
    ));
    
    $this->widgetSchema->setNameFormat('searchExpedition[%s]');

    $this->setValidators(array(
      'name'    => new sfValidatorString(array('required' => false, 'trim' => true)),
      'date_from'    => new sfValidatorString(array('required' => false, 'trim' => true)),
    ));

  }
  public function getCurrentCulture()
  {
    return isset($this->options['culture']) ? $this->options['culture'] : 'en';
  }
  public function getMonthFormat()
  {
    return isset($this->options['month_format']) ? $this->options['month_format'] : 'short_name';
  }
}