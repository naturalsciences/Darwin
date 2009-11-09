<?php

class SearchExpeditionForm extends DarwinForm
{

  public function configure()
  {
    $years = range(1800, date('Y'));
    $name_options = array($this->getI18N()->__('begins with'), $this->getI18N()->__('contains'));
    $this->setWidgets(array(
      'name_options' => new sfWidgetFormChoice(array('choices' => $name_options)),
      'name' => new sfWidgetFormInput(),
      'date_range' => new sfWidgetFormDateRange(array('from_date' => new sfWidgetFormI18nDate(array('years' => array_combine($years, $years), 'culture' => $this->getCurrentCulture(), 'month_format' => $this->getMonthFormat(), )), 'to_date' => new sfWidgetFormI18nDate(array('years' => array_combine($years, $years), 'culture' => $this->getCurrentCulture(), 'month_format' => $this->getMonthFormat(), )), 'template' => '%from_date%&nbsp;</td><td>%to_date%',)),
    ));
    
    $this->widgetSchema->setNameFormat('searchExpedition[%s]');

    $this->setValidators(array(
      'name_options' => new sfValidatorChoice(array('choices' => array_keys($name_options))),
      'name'    => new sfValidatorString(array('required' => false, 'trim' => true)),
      'date_range' => new sfValidatorDateRange(array('from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
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