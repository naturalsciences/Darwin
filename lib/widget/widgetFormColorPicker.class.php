<?php
/**
 * widgetFormColorPicker: generates a Jquery Colorpicker widget.
 *
 * @package    symfony
 * @subpackage form
 * @author     DaRWIN2 team <collections@naturalsciences.be>
 */

class widgetFormColorPicker extends sfWidgetFormInput
{
  /**
   * Gets the Stylesheets paths associated with the widget.
   *
   * @return array An array of Stylesheets paths
   */
  public function getStylesheets()
  {
    return array('/css/farbtastic.css' => 'screen');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavascripts()
  {
    return array('/js/farbtastic.js');
  }

 public function render($name, $value = null, $attributes = array(), $errors = array())
  {
//     use_helper("jQuery");

/*    $attributes['style'] = "width: 4em;";*/
    $html = parent::render($name, $value, $attributes, $errors);

    $html .= "<div id=\"colorpicker\"></div>
   <script type=\"text/javascript\">
     $(document).ready(function() {
       
       $('#" . $this->generateId($name) . "').css('backgroundColor', '#' + '" . (($value) ? $value : 'ededee') . "');

       $('#colorpicker').farbtastic('#" . $this->generateId($name) . "');
     });
   </script>
    ";

    return $html;
  }
}
