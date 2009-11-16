<?php

class widgetFormButtonRef extends sfWidgetFormInputHidden
{

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $values = array_merge(array('text' => '', 'is_empty' => false), is_array($value) ? $value : array());
        $coll_name = $this->getName($value);
        $input = parent::render($name, $value, $attributes, $errors);
	$input .= $this->renderContentTag('div',$this->escapeOnce($coll_name), array(
	   'id' => $this->generateId($name)."_name",
	   'class' => "ref_name",
	));

	if($this->getOption('nullable'))
	{
	  $options = array(
	    'src' => '/images/widget_help_close.png',
	    'class' => 'ref_clear'
	  );

	  if($coll_name == '')
	    $options['class'] .= ' hidden';
	  $input .= $this->renderTag('img',$options);
	}
	$input .= '<div title="'.$this->getOption('box_title').'" id="'.$this->generateId($name).'_button" class="button">';
	$input .= image_tag('button_grey_left.png', array('class' => 'left_part' ));

	$input .= link_to( $coll_name=='' ? __('Choose !') : __('Change !'),
	    $this->getOption('link_url'),
	    array('class' => 'but_text' )
	); 

	$input .= image_tag('button_grey_right.png', array('class' => 'right_part' ));
        $input .= '</div>';
	$input .= $this->renderContentTag('div','&nbsp;',array('class' => 'clear'));

        return $input;
    }
    
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $this->addRequiredOption('model');
        $this->addOption('method', '__toString');
	$this->addOption('nullable', false);

//         $this->addOption('connection', null);
//         $this->addOption('table_method', null);
	$this->setOption('is_hidden', false);
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