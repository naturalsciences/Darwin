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
        $this->addOption('fieldsHidders', array());
    }

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $values = array_merge(array('text' => '', 'is_empty' => false), is_array($value) ? $value : array());
        $obj_name = $this->getName($value);
        $obj_id = $this->generateId($name)."_name";
        $input = parent::render($name, $value, $attributes, $errors);
        $attributes = array_merge($attributes, array('id' => $obj_id,'class' => 'large_size'));
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

        $script_header = '<script type="text/javascript">';
        $script_footer = '</script>';

        $script_formated = sprintf('jQuery("#%1$s").focus(function() 
                                    {
                                      if (jQuery("div.search_box:hidden").length)
                                      {
                                        jQuery("div.search_box, ul.tab_choice").slideDown();
                                      }
                                      jQuery("div.search_box table#search_and_choose tbody td:first input:first").focus();
                                    });
                                   ',
                                   $obj_id
                                  );

        foreach ($this->getOption('fieldsHidders') as $key=>$value)
        {
          $script_formated .= sprintf('jQuery("#%1$s").focus(function() 
                                       {
                                         if (jQuery("div.search_box:visible").length)
                                         {
                                           jQuery("div.search_box, ul.tab_choice").slideUp();
                                         }
                                       });
                                      ',
                                       $value
                                     );
        }        

        return $input .
               $script_header .
               $script_formated .
               $script_footer;
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