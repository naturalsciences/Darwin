<?php

class widgetFormButtonRef extends sfWidgetFormInputHidden
{

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $class = array('class'=>'');
        if (isset($attributes['class']))
        {
          $class = array_merge($class, $attributes);
          $attributes['class'] .= ' hidden';
        }
        $class = ' '.$class['class'];
        $values = array_merge(array('text' => '', 'is_empty' => false), is_array($value) ? $value : array());
        $obj_name = $this->getName($value);       
        $input = parent::render($name, $value, $attributes, $errors);
	$input .= $this->renderContentTag('div',$obj_name, array(
	   'id' => $this->generateId($name)."_name",
	   'class' => "ref_name" . $class,
	));
        
	if($this->getOption('nullable'))
	{
	  $options = array(
	    'src' => '/images/remove.png',
            'id' => $this->generateId($name)."_clear",
	    'class' => "ref_clear" . $class
	  );

	  if($value == 0)
	    $options['class'] .= ' hidden';
	  $input .= $this->renderTag('img',$options);
	}
        
        if (strlen($this->getOption('button_class')) > 0)
        {
          $class .= ' '.$this->getOption('button_class');
        }

	if($this->getOption('button_is_hidden') && $value == 0)
	{
	  $class .= ' hidden';
	}
	$input .= '<div title="'.$this->getOption('box_title').'" id="'.$this->generateId($name).'_button" class="ref_name ' .$class. '">';

	$input .= link_to( ($obj_name=='' || $obj_name=='-') ? __('Choose !') : __('Change !'),
	    $this->getOption('link_url'),
	    array('class' => 'but_text' . $class )
	); 

	$input .= '</div>';

        return $input;
    }
    
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $this->addRequiredOption('model');
        $this->addOption('method', '__toString');
	$this->addOption('nullable', false);
	$this->addOption('is_hidden', false);
	$this->addOption('button_is_hidden', false);
        $this->addRequiredOption('link_url');
	$this->addRequiredOption('box_title');
        $this->addOption('button_class', 'button');
    }

    public function getJavaScripts()
    {
      return array('/js/button_ref.js');
    }

    public function getName($value)
    {
        if(is_numeric($value))
            $object = Doctrine::getTable($this->getOption('model'))->find($value);
        else
            return '-';
        if(! $object)
            return '-';
        $method = $this->getOption('method');
        try
        {
            return  $object->$method();
        } catch (Exception $e) {
            throw $e;
        }
    }

}