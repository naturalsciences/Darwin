<?php

class widgetFormSelectComplete extends sfWidgetFormDoctrineChoice
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('change_label');
    $this->addRequiredOption('add_label');
    $this->addOption('forced_choices', false);
    parent::configure($options, $attributes);
  }

  public function getChoices()
  {
    if(! isset($this->choices))
    {
      if($this->getOption('forced_choices') !== false )
	$this->choices = $this->getOption('forced_choices');
      else
	$this->choices = parent::getChoices();
    }
    return $this->choices;
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
      $this->getChoices();
      $widget = '<div id="'.$this->generateId($name).'_parent">';

      if( array_key_exists($value,$this->choices))
      {
	$widget .= $this->renderTag('input', array( 'type' => 'text', 'id' => $id = $this->generateId($name).'_input', 'class' => 'hidden'));
	$widget .= parent::render($name, $value, $attributes, $errors);
	$add_class='';
	$pick_class=' hidden';
      }
      else
      {
	$widget .= $this->renderTag('input', array( 'type' => 'text', 'id' => $id = $this->generateId($name).'_input', 'value' => $value, 'name'=> $name));
	$widget .= parent::render('', $value, array_merge(array('class'=>'hidden'),$attributes), $errors);
	$add_class=' hidden';
	$pick_class='';
      }
      $widget .= '<div class="add_item_button'. $add_class .'">'.$this->renderTag('img',array('src'=>'/images/add_green.png', 'alt'=>'+')).__($this->getOption('add_label')).'</div>';
      $widget .= '<div class="change_item_button'. $pick_class .'">'.$this->renderTag('img',array('src'=>'/images/refresh_green.png', 'alt'=>'+')).__($this->getOption('change_label')).'</div>';
      $widget .= '</div>';
      
      $widget .=  sprintf(<<<EOF
<script type="text/javascript">
$(document).ready(function () {
    $('#%1\$s .add_item_button').click(function()
    {
      parent_id = '%1\$s';
      el = $('#'+parent_id +' select');
      el.hide();
      $('#'+parent_id +' input').attr('name', el.attr('name'))
      $('#'+parent_id +' input').show();
      el.removeAttr('name');
      $('#'+parent_id +' .change_item_button').show();
      $('#'+parent_id +' .add_item_button').hide();
    });

    $('#%1\$s .change_item_button').click(function()
    {
	parent_id = '%1\$s';
        el = $('#'+parent_id +' input');
	el.hide();
	$('#'+parent_id +' select').attr('name', el.attr('name'));
	$('#'+parent_id +' select').show();
	el.removeAttr('name');
        $('#'+parent_id +' .add_item_button').show();
        $('#'+parent_id +' .change_item_button').hide();
    });
});
</script>
EOF
    , $this->generateId($name).'_parent');
      return $widget;
  }
}