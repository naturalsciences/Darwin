<?php

class SearchExpeditionForm extends DarwinForm
{

  public function configure()
  {
    $years = range(1800, date('Y'));
    $this->setWidgets(array(
      'name' => new sfWidgetFormInput(),
      'date_range' => new sfWidgetFormDateRange(array(
        'from_date' => new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
							   'image'=>'/images/calendar.gif', 
							   'format' => '%day%/%month%/%year%', 
							   'years' => $years, )),
        'to_date' => new widgetFormJQueryFuzzyDate(array('culture'=>$this->getCurrentCulture(), 
                                                         'image'=>'/images/calendar.gif', 
							 'format' => '%day%/%month%/%year%', 
							 'years' => $years, )),
        'template' => '<td>%from_date%</td>&nbsp;&nbsp;<td>%to_date%</td>',
        ))
    ));
    
    $this->widgetSchema->setNameFormat('searchExpedition[%s]');

    $this->setValidators(array(
      'name'    => new sfValidatorString(array('required' => false, 'trim' => true)),
      'date_range' => new sfValidatorPass(),
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