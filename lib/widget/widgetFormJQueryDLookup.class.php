<?php

class widgetFormJQueryDLookup extends sfWidgetFormInputText
{

    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $this->addRequiredOption('model');
        $this->addOption('method', '__toString');
        $this->addOption('nullable', false);
        $this->addOption('is_hidden', false);
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $values = array_merge(array('text' => '', 'is_empty' => false), is_array($value) ? $value : array());
        $obj_name = $this->getName($value);
        $input = parent::render($name, $value, $attributes, $errors);
        $attributes = array_merge($attributes, array('id' => $this->generateId($name)."_name",'class' => 'large_size'));
        $input .= parent::render('', $obj_name, $attributes, $errors);

        if($this->getOption('nullable'))
        {
          $options = array(
            'src' => '/images/remove.png',
            'class' => 'reference_clear'
          );

          if($obj_name == '')
            $options['class'] .= ' hidden';
          $input .= $this->renderTag('img',$options);
        }
        return $input;
    }

    public function getJavaScripts()
    {
      return array('/js/DLookup.js');
    }
    
    public function getName($value)
    {
        if(is_numeric($value))
            $object = Doctrine::getTable($this->getOption('model'))->find($value);
        else
            return '';
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