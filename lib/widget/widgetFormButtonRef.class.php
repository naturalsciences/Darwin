<?php

class widgetFormButtonRef extends sfWidgetFormInputHidden
{

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $values = array_merge(array('text' => '', 'is_empty' => false), is_array($value) ? $value : array());
        $obj_name = $this->getName($value);
        $input = parent::render($name, $value, $attributes, $errors);
	$input .= $this->renderContentTag('div',$this->escapeOnce(($obj_name == '')?'-':$obj_name), array(
	   'id' => $this->generateId($name)."_name",
	   'class' => "ref_name",
	));
        
	if($this->getOption('nullable'))
	{
	  $options = array(
	    'src' => '/images/remove.png',
	    'class' => 'ref_clear'
	  );

	  if($obj_name == '')
	    $options['class'] .= ' hidden';
	  $input .= $this->renderTag('img',$options);
	}

        $class = 'ref_name button';
	if(! $this->getOption('button_is_hidden'))
	{
          if ($value == 0)
            $class .= ' hidden';
	  $input .= '<div title="'.$this->getOption('box_title').'" id="'.$this->generateId($name).'_button" class="'.$class.'">';
	  $input .= image_tag('button_grey_left.png', array('class' => 'left_part' ));

	  $input .= link_to( $obj_name=='' ? __('Choose !') : __('Change !'),
	      $this->getOption('link_url'),
	      array('class' => 'but_text' )
	  ); 

	  $input .= image_tag('button_grey_right.png', array('class' => 'right_part' ));
          $input .= '</div>';
/*	  $input .= $this->renderContentTag('div','&nbsp;',array('class' => 'clear'));*/
	}

        if($this->getOption('wrong_parent_warning'))
        {
          $class .= ' warn_message hidden';
          $input .= $this->renderContentTag('div', 
                                            __('The parenty does not follow the possible upper level rule'), 
                                            array(
                                                   'id' => $this->generateId($name).'_warning',
                                                   'class' => $class
                                                 )
                                           );
        }
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
	$this->addOption('wrong_parent_warning', false);
        $this->addRequiredOption('link_url');
	$this->addRequiredOption('box_title');
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