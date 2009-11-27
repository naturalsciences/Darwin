<?php

/*
 * This file is part of the darwin package.
 * (c) Paul-Andre Duchesne <Paul-Andre.Duchesne@naturalsciences.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * fuzzyDateValidatorSchemaCompare compares several values from an array.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Paul-Andre Duchesne <Paul-Andre.Duchesne@naturalsciences.be>
 */
class fuzzyDateValidatorSchemaCompare extends sfValidatorSchemaCompare
{
  protected function doClean($values)
  {
    $old_left_field = $values[$this->getOption('left_field')];
    $old_right_field = $values[$this->getOption('right_field')];
    $values[$this->getOption('left_field')]=$values[$this->getOption('left_field')][$this->getOption('left_field')]->format('U');
    $values[$this->getOption('right_field')]=$values[$this->getOption('right_field')][$this->getOption('right_field')]->format('U');
    $values = parent::doClean($values);
    $values[$this->getOption('left_field')] = $old_left_field;
    $values[$this->getOption('right_field')] = $old_right_field;
    return $values;
  }
}

?>