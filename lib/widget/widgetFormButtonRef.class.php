<?php

class widgetFormButtonRef extends sfWidgetFormInputHidden
{

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $values = array_merge(array('text' => '', 'is_empty' => false), is_array($value) ? $value : array());
        $name = $this->getName($value);
        $input = parent::render($name, $value, $attributes, $errors);
        $input .= '<div id="'.$this->generateId($name).'_name">'.$name.'</div>';
        $input .= '<div id="'.$this->generateId($name).'_button" class="button">';
        $input .= '<img class="left_part" src="/images/button_grey_left.png" alt=""/>';
        $input .= '<span class="but_text" class="button">';
        if($name == '')
            $input .= __('Choose !');
        else
            $input .= __('Change !');
        $input .= '</span>';
        $input .= '<img class="right_part" src="/images/button_grey_right.png" alt=""/>';
        $input .= '</div><div style="clear: left;"> </div>';

        return $input;
    }
    
    protected function configure($options = array(), $attributes = array())
    {
        $this->addRequiredOption('model');
        $this->addOption('method', '__toString');
//         $this->addOption('connection', null);
//         $this->addOption('table_method', null);
        parent::configure($options, $attributes);
    }
    
    public function getName($value)
    {
        $object = Doctrine::getTable($this->getOption('model'))->find($value);
        if(! $object)
            return '';
        $method = $this->getOption('method');
        try
        {
            return  $object->$method();
        } catch (Exception $e) {
            throw $e;
        }
    }
}