<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Jonathan H. Wage <jonwage@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormDoctrineChoice represents a choice widget for a model.
 *
 * @package    symfony
 * @subpackage doctrine
 * @author     Paul-André Duchesne <Paul-Andre.Duchesne@naturalsciences.be>
 */
class widgetFormSelectDoubleListFilterable extends sfWidgetFormSelectDoubleList
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    $this->addOption('filter_class', 'double_list_filter_input');
    /* Flag for add option input box activation */
    $this->addOption('add_active', false);
    /* Class used for add option input stylin' */
    $this->addOption('add_class', 'double_list_add_input');
    /* Add action url */
    $this->addOption('add_url', '');
    $this->addOption('template', <<<EOF
<div class="%class%">
  <div class="double_list_filter">%filter%%filter_reset%</div>
  <div style="float: left">
    <div class="double_list_filter_label">%label_unassociated%</div>
    %unassociated%
  </div>
  <div style="float: left; margin-top: 2em">
    %unassociate%
    <br />
    %associate%
  </div>
  <div style="float: left">
    <div class="double_list_filter_label">%label_associated%</div>
    %associated%
  </div>
  <div class="double_list_filter_add">
    <div>%add_option%</div>
  </div>
  <br style="clear: both" />
  <script type="text/javascript">
    sfDoubleList.init(document.getElementById('%id%'), '%class_select%');
    function search_list()
    {
      $('#unassociated_%id% option').each(function()
      {
        exp='.*'+$('#filter_%id%').val()+'.*';
        Expression = new RegExp(exp,'gi');
        if(!Expression.test(this.innerHTML))
        {
          $(this).hide();
        }
        else
        {
          $(this).show();
        }
      });
    }
    function reset_list()
    {
      $('#unassociated_%id% option').each(function()
      {
          $(this).show();
          $('#filter_%id%').val('');
      });
    }
    $('#filter_%id%').bind('keyup', search_list );
    $('#filter_%id%_clear').bind('click', reset_list );
  </script>
</div>
EOF
    );

  }

  /**
   * Renders the widget.
   *
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_null($value))
    {
      $value = array();
    }

    $choices = $this->getOption('choices');
    if ($choices instanceof sfCallable)
    {
      $choices = $choices->call();
    }

    $associated = array();
    $unassociated = array();
    foreach ($choices as $key => $option)
    {
      if (in_array(strval($key), $value))
      {
        $associated[$key] = $option;
      }
      else
      {
        $unassociated[$key] = $option;
      }
    }

    $size = isset($attributes['size']) ? $attributes['size'] : (isset($this->attributes['size']) ? $this->attributes['size'] : 10);
    $id = $this->generateId($name);
    $associatedWidget = new sfWidgetFormSelect(array('multiple' => true, 'choices' => $associated), array('size' => $size, 'class' => $this->getOption('class_select').'-selected'));
    $unassociatedWidget = new sfWidgetFormSelect(array('multiple' => true, 'choices' => $unassociated), array('size' => $size, 'class' => $this->getOption('class_select')));
    $filterWidget = new sfWidgetFormInputText(array(), array('class'=>$this->getOption('filter_class')));
    $filterResetOptions = array('src' => '/images/remove.png',
                                'id' => 'filter_'.$id."_clear",
                                'class' => 'filter_clear',
                                'alt' => __('Reset filter'),
                                'title' => __('Reset filter')
                               );
    $addOptionHTML = '';
    if ($this->getOption('add_active'))
    {
      $addOptionWidget = new sfWidgetFormInputText(array(), array('id' => 'add_'.$id.'_input', 'class'=>$this->getOption('add_class')));
      $addOptionWidget->setLabel(__('Add new value:'));
      $addImageOptions = array('src' => '/images/add_green.png',
                              'id' => 'add_'.$id.'_image',
                              'class' => 'add_option',
                              'alt' => __('Add value'),
                              'title' => __('Add value')
                              );
      $addLinkOptions = array('id'=> 'add_'.$id.'_link',
                              'class' => 'add_option',
                              'href' => url_for($this->getOption('add_url'))
                              );
      $addOptionHTML = $this->renderContentTag('label',$addOptionWidget->getLabel(), array('for'=>'add_'.$id.'_input')).$addOptionWidget->render('add_'.$name).$this->renderContentTag('a',$this->renderTag('img', $addImageOptions), $addLinkOptions);
    }

    return strtr($this->getOption('template'), array(
      '%class%'              => $this->getOption('class'),
      '%class_select%'       => $this->getOption('class_select'),
      '%id%'                 => $id,
      '%label_associated%'   => $this->getOption('label_associated'),
      '%label_unassociated%' => $this->getOption('label_unassociated'),
      '%associate%'          => sprintf('<a href="#" onclick="%s">%s</a>', 'sfDoubleList.move(\'unassociated_'.$id.'\', \''.$id.'\'); return false;', $this->getOption('associate')),
      '%unassociate%'        => sprintf('<a href="#" onclick="%s">%s</a>', 'sfDoubleList.move(\''.$id.'\', \'unassociated_'.$id.'\'); return false;', $this->getOption('unassociate')),
      '%associated%'         => $associatedWidget->render($name),
      '%unassociated%'       => $unassociatedWidget->render('unassociated_'.$name),
      '%filter%'             => $filterWidget->render('filter_'.$name),
      '%filter_reset%'       => $this->renderTag('img', $filterResetOptions),
      '%add_option%'         => $addOptionHTML
    ));
  }

}