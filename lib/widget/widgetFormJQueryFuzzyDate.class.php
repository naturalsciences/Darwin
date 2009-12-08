<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormJQueryDate represents a date widget rendered by JQuery UI.
 *
 * This widget needs JQuery and JQuery UI to work.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormJQueryDate.class.php 16262 2009-03-12 14:02:33Z fabien $
 */
class widgetFormJQueryFuzzyDate extends sfWidgetFormDate
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * image:   The image path to represent the widget (false by default)
   *  * config:  A JavaScript array that configures the JQuery date widget
   *  * culture: The user culture
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('image', false);
    $this->addOption('config', '{}');
    $this->addOption('culture', '');
    $this->addOption('with_time', false);
    $this->addOption('with_seconds', true);
    parent::configure($options, $attributes);

    if ('en' == $this->getOption('culture'))
    {
      $this->setOption('culture', 'en');
    }
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $prefix = $this->generateId($name);

    $image = '';
    if (false !== $this->getOption('image'))
    {
      $image = sprintf(', buttonImage: "%s", buttonImageOnly: true', $this->getOption('image'));
    }

    $time_str = '';
    if($this->getOption('with_time'))
    {
      $widget = new sfWidgetFormTime(array(
 	'empty_values' => array_merge(array('hour'=>'','minute'=>'','second'=>''), $this->getOption('empty_values')),
	'with_seconds' => $this->getOption('with_seconds')
      ));
      $time_str = $widget->render($name, $value, $attributes, $errors);
    }
    
    return parent::render($name, $value, $attributes, $errors).
           $this->renderTag('input', array('type' => 'hidden', 'size' => 10, 'id' => $id = $this->generateId($name).'_jquery_control', 'disabled' => 'disabled')).
           sprintf(<<<EOF
<script type="text/javascript">
  function wfd_%s_read_linked()
  {
    
    var day = 1;
    var month = 1;
    var year = %s;
    if (jQuery("#%s").val() != "")
    {
      year = jQuery("#%s").val();
    }
    else if (jQuery("#%s").hasClass("to_date"))
    {
      year = %s;
    }

    if (jQuery("#%s").val() != "")
    {
      month = jQuery("#%s").val();
    }
    else if (jQuery("#%s").hasClass("to_date"))
    {
      month = 12;
    }
    
    if (jQuery("#%s").val() == "")
    {
      jQuery("#%s").val("");
    }

    var daysInMonth = 32 - new Date(year, month - 1, 32).getDate();

    if ((jQuery("#%s").val() != "") && (jQuery("#%s").val() != "") && (jQuery("#%s").val() != ""))
    {
      day = jQuery("#%s").val();
    }
    else if (jQuery("#%s").hasClass("to_date"))
    {
      day = daysInMonth;
    }

    jQuery("#%s").val(year + "-" + month + "-" + day);

    return {};
  }

  function wfd_%s_update_linked(date)
  {
    jQuery("#%s").val(date.substring(0, 4));
    jQuery("#%s").val(date.substring(5, 7));
    jQuery("#%s").val(date.substring(8));
  }

  function wfd_%s_check_linked_days()
  {
    var daysInMonth = 32 - new Date(jQuery("#%s").val(), jQuery("#%s").val() - 1, 32).getDate();
    jQuery("#%s option").attr("disabled", "");
    jQuery("#%s option:gt(" + (%s) +")").attr("disabled", "disabled");

    if (jQuery("#%s").val() > daysInMonth)
    {
      jQuery("#%s").val(daysInMonth);
    }
  }

  jQuery(document).ready(function() {
    jQuery("#%s").datepicker(jQuery.extend({}, {
      minDate:    new Date(%s, 1 - 1, 1),
      maxDate:    new Date(%s, 12 - 1, 31),
      yearRange:  '%s:%s',
      beforeShow: wfd_%s_read_linked,
      onSelect:   wfd_%s_update_linked,
      changeMonth: true,
      changeYear: true,
      showButtonPanel: false,
      showOn:     "button"
      %s
    }, jQuery.datepicker.regional["%s"], %s, {dateFormat: "yy-mm-dd"}));
  });

  jQuery("#%s, #%s, #%s").change(wfd_%s_check_linked_days);
</script>
EOF
      ,
      $prefix, 
      min($this->getOption('years')),
      $this->generateId($name.'[year]'), $this->generateId($name.'[year]'), $this->generateId($name.'[year]'),
      date('Y'),
      $this->generateId($name.'[month]'), $this->generateId($name.'[month]'), $this->generateId($name.'[month]'),
      $this->generateId($name.'[month]'), $this->generateId($name.'[day]'),
      $this->generateId($name.'[year]'), $this->generateId($name.'[month]'), $this->generateId($name.'[day]'), 
      $this->generateId($name.'[day]'), 
      $this->generateId($name.'[year]'), 
      $id,
      $prefix,
      $this->generateId($name.'[year]'), $this->generateId($name.'[month]'), $this->generateId($name.'[day]'),
      $prefix,
      $this->generateId($name.'[year]'), $this->generateId($name.'[month]'),
      $this->generateId($name.'[day]'), $this->generateId($name.'[day]'),
      ($this->getOption('can_be_empty') ? 'daysInMonth' : 'daysInMonth - 1'),
      $this->generateId($name.'[day]'), $this->generateId($name.'[day]'),
      $id,
      min($this->getOption('years')), max($this->getOption('years')),
      min($this->getOption('years')), max($this->getOption('years')),
      $prefix, $prefix, $image, $this->getOption('culture'), $this->getOption('config'),
      $this->generateId($name.'[day]'), $this->generateId($name.'[month]'), $this->generateId($name.'[year]'),
      $prefix
    ).$time_str;
  }
}
