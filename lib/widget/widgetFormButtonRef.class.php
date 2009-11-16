<?php

class widgetFormButtonRef extends sfWidgetFormInputHidden
{

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $values = array_merge(array('text' => '', 'is_empty' => false), is_array($value) ? $value : array());
        $coll_name = $this->getName($value);
        $input = parent::render($name, $value, $attributes, $errors);
        $input .= '<div id="'.$this->generateId($name).'_name" class="ref_name">'.$coll_name.'</div>';
	if($this->getOption('nullable'))
	{
	  $input .= '<img class="ref_clear';
	  if($coll_name == '')
	    $input .= ' hidden';
	  $input .= '" src="/images/widget_help_close.png"/>';
	}
        $input .= '<div title="'.$this->getOption('box_title').'" id="'.$this->generateId($name).'_button" class="button">';
        $input .= '<img class="left_part" src="/images/button_grey_left.png" alt=""/>';
        $input .= '<a class="but_text" src="'.url_for($this->getOption('link_url')).'">';
        if($coll_name == '')
            $input .= __('Choose !');
        else
            $input .= __('Change !');
        $input .= '</a>';
        $input .= '<img class="right_part" src="/images/button_grey_right.png" alt=""/>';
        $input .= '</div><div style="clear: left;"> </div>';

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