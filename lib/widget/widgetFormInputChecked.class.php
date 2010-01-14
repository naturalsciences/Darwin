<?php

class widgetFormInputChecked extends sfWidgetFormInputHidden
{
    protected function configure($options = array(), $attributes = array())
    {
        parent::configure($options, $attributes);
        $this->addRequiredOption('model');
        $this->addOption('method', '__toString');
        $this->addOption('link_url', '');
        $this->addOption('notExistingAddDisplay', true);
        $this->addOption('notExistingAddTitle', 'This entry do not exist. Would you like we had it ?');
        $this->addOption('notExistingAddValues', array('Yes', 'No'));
        $this->addOption('notExistingAddSelected', 0);
        $this->addOption('nullable', false);
        $this->setOption('is_hidden', false);
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

    public function render($name, $value = null, $attributes = array(), $errors = array())
    {
        $values = array_merge(array('text' => '', 'is_empty' => false), is_array($value) ? $value : array());
        $obj_name = $this->getName($value);
        $input = '<ul><li>';
        $input .= parent::render($name, $value, $attributes, $errors);
        $input .= $this->renderTag('input',
                                   array('id' => $this->generateId($name)."_name",
                                         'type' => 'text',
                                         'value' => $this->escapeOnce($obj_name),
                                        )
                                  );
        $input .= '</li>';
        if($this->getOption('notExistingAddDisplay'))
        {
          $input .= '<li class="hidden">'.
                    '<label for="'.$this->generateId($name).'_check">'.$this->getOption('notExistingAddTitle').':</label>'.
                    '<select id="'.$this->generateId($name).'_check">';
          foreach ($this->getOption('notExistingAddValues') as $key => $option)
          {
            $input .= $this->renderContentTag('option',
                                              $option,
                                              array('selected' => ($this->getOption('notExistingAddSelected') == $key)?"selected":"",
                                                    'value' => $key,
                                                   )
                                             );
          }
        }
        $input .= '</select></li></ul>';
        $input .= sprintf(<<<EOF
<script type="text/javascript">
$(document).ready(function () {
    $('#%1\$s').live('change', function()
    {
      $('#%2\$s').val('');
      selectMesg = '#%3\$s';
      $(selectMesg).parent().removeClass('show').addClass('hidden');
      $(selectMesg + ' option:first').attr('selected', true);
      $(selectMesg + ' option:last').attr('selected', false);
      /*parent_id = '%1\$s';
      el = $('#'+parent_id +' select');
      el.removeClass('show').addClass('hidden');
      $('#'+parent_id +' input').attr('name', el.attr('name'))
      $('#'+parent_id +' input').removeClass('hidden').addClass('show');
      el.removeAttr('name');
      $('#'+parent_id +' .change_item_button').removeClass('hidden').addClass('show');
      $('#'+parent_id +' .add_item_button').removeClass('show').addClass('hidden');*/
    });

});
</script>
EOF
    , $this->generateId($name).'_name',
      $this->generateId($name),
      $this->generateId($name).'_check');
        return $input;
     }  
}