<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorDate validates a date. It also converts the input value to a valid date.
 *
 * @package    darwin2
 * @subpackage validator
 * @author     Paul-Andre Duchesne <Paul-Andre.Duchesne@naturalsciences.be>
 * @version    SVN: $Id: sfValidatorDate.class.php 13278 2008-11-23 15:04:24Z FabianLange $
 */
class fuzzyDateValidator extends sfValidatorDate
{
  /**
   * Configures the current validator.
   *
   * Available options from sfValidatorDate:
   *
   *  * date_format:             A regular expression that dates must match
   *  * with_time:               true if the validator must return a time, false otherwise
   *  * date_output:             The format to use when returning a date (default to Y-m-d)
   *  * datetime_output:         The format to use when returning a date with time (default to Y-m-d H:i:s)
   *  * date_format_error:       The date format to use when displaying an error for a bad_format error (use date_format if not provided)
   *  * max:                     The maximum date allowed (as a timestamp)
   *  * min:                     The minimum date allowed (as a timestamp)
   *  * from_date:               true if the date to validate is a begin date, false if an end date in a range
   *  * date_format_range_error: The date format to use when displaying an error for min/max (default to d/m/Y H:i:s)
   *
   * Available error codes:
   *
   *  * bad_format
   *  * min
   *  * max
   *  * year_missing
   *  * month_missing
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   *
   * @see sfValidatorDate
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('year_missing', 'Year missing.');
    $this->addMessage('month_missing', 'Month missing or remove the day and time.');
    $this->addMessage('day_missing', 'Day missing or remove the time.');
    $this->addMessage('hour_missing', 'Hour missing or remove the time.');
    $this->addMessage('minute_missing', 'minute missing or remove the seconds.');
    $this->addMessage('time_without_date', 'Day missing or remove the time.');
    $this->addMessage('wrong_date_part_length', 'A part of date is wrong.');
    $this->addOption('from_date', true);

    parent::configure($options, $messages);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    if (is_array($value))
    {
      try
      {
        $checkDateStructure = FuzzyDateTime::checkDateTimeStructure($value);
      }
      catch (Exception $e)
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
      if (!empty($checkDateStructure)) throw new sfValidatorError($this, $checkDateStructure, array('value' => $value));
      try
      {
        $clean = new FuzzyDateTime($value, 
                                   FuzzyDateTime::getMaskFromDate($value), 
                                   $this->getOption('from_date'), 
                                   $this->getOption('with_time')
                                  );
      }
      catch (Exception $e)
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }
    else if ($regex = $this->getOption('date_format'))
    {
      if (!preg_match($regex, $value, $match))
      {
        throw new sfValidatorError($this, 'bad_format', array('value' => $value, 'date_format' => $this->getOption('date_format_error') ? $this->getOption('date_format_error') : $this->getOption('date_format')));
      }
      $clean = new FuzzyDateTime($match);
    }
    else 
    {
      try 
      {
        $clean = new FuzzyDateTime($value);
      }
      catch (Exception $e)
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }

    if ($this->hasOption('max') && $clean > $this->getOption('max'))
    {
      throw new sfValidatorError($this, 'max', array('value' => $value, 'max' => $this->getOption('max')));
    }

    if ($this->hasOption('min') && $clean < $this->getOption('min'))
    {
      throw new sfValidatorError($this, 'min', array('value' => $value, 'min' => $this->getOption('min')));
    }

    return $clean;

  }
}
