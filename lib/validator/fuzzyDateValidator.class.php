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
    $this->addMessage('month_missing', 'Month missing or remove the day.');

    $this->addOption('from_date', true);

    parent::configure($options, $messages);
  }

  /**
   * Converts an array representing a date to a timestamp.
   *
   * The array can contains the following keys: year, month, day, hour, minute, second
   *
   * @param  array $value  An array of date elements
   *
   * @return int A timestamp
   */
  protected function convertDateArrayToTimestamp($value)
  {
    // all elements must be empty or a number
    foreach (array('year', 'month', 'day', 'hour', 'minute', 'second') as $key)
    {
      if (isset($value[$key]) && !preg_match('#^\d+$#', $value[$key]) && !empty($value[$key]))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }
    }

    // if one date value is empty, all others must be empty too
    $empties =
      (!isset($value['year']) || !$value['year'] ? 0 : 4) +
      (!isset($value['month']) || !$value['month'] ? 0 : 2) +
      (!isset($value['day']) || !$value['day'] ? 0 : 1)
    ;
    
    if ($empties == 0)
    {
      return $this->getEmptyValue();
    }
    else if ($empties <= 3)
    {
      throw new sfValidatorError($this, 'year_missing', array('value' => $value));
    }
    else if ($empties == 4)
    {
      $value['day'] = ($this->getOption('from_date'))?'01':'31';
      $value['month'] = ($this->getOption('from_date'))?'01':'12';
    }
    else if ($empties == 5)
    {
      throw new sfValidatorError($this, 'month_missing', array('value' => $value));
    }
    else if ($empties == 6)
    {
      $value['day'] = ($this->getOption('from_date'))?'01':strval(date('d', mktime(0, 0, 0, intval($value['month'])+1, 0, intval($value['year']))));
    }
    
    if (!checkdate(intval($value['month']), intval($value['day']), intval($value['year'])))
    {
      throw new sfValidatorError($this, 'invalid', array('value' => $value));
    }

    if ($this->getOption('with_time'))
    {
      // if second is set, minute and hour must be set
      // if minute is set, hour must be set
      if (
        $this->isValueSet($value, 'second') && (!$this->isValueSet($value, 'minute') || !$this->isValueSet($value, 'hour'))
        ||
        $this->isValueSet($value, 'minute') && !$this->isValueSet($value, 'hour')
      )
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $value));
      }

      $clean = mktime(
        isset($value['hour']) ? intval($value['hour']) : 0,
        isset($value['minute']) ? intval($value['minute']) : 0,
        isset($value['second']) ? intval($value['second']) : 0,
        intval($value['month']),
        intval($value['day']),
        intval($value['year'])
      );
    }
    else
    {
      $clean = mktime(0, 0, 0, intval($value['month']), intval($value['day']), intval($value['year']));
    }

    if (false === $clean)
    {
      throw new sfValidatorError($this, 'invalid', array('value' => var_export($value, true)));
    }

    return $clean;
  }
}
