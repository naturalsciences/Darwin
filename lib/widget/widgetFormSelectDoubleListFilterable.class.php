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
<ul id=%error_id% class="error_list" style="display:none;">
  <li></li>
</ul>
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
    // Filter unassociated select box based on matching of text entered as a part of option text
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
    // Reset the filter and redisplay all unassociated select options
    function reset_list()
    {
      $('#unassociated_%id% option').each(function()
      {
          $(this).show();
          $('#filter_%id%').val('');
      });
    }
    // Bind the filter function to keyUp event of filter input
    $('#filter_%id%').bind('keyup', search_list );
    // Bind the remove filter function to click event of filter clear image
    $('#filter_%id%_clear').bind('click', reset_list );

    $(document).ready(function () {
      // Click on add option link try to add an option in the corresponding table providing values to this widget
      $('a#add_%id%_link').click(function(){
        // Hide and empty errors list first
        $('#%error_id%').hide();
        $('#%error_id%').find('li').text(' ');
        var input_value = $('#add_%id%').val();
        // Trigger the insertion only if there is a value
        if(input_value.length)
        {
          // Create and fill an array with all the unassociated select options values
          var unassociatedList = new Array();
          $('#unassociated_%id% option').each(function()
          {
            unassociatedList.push($(this).text());
          });
          // Add the potential new value to the list
          unassociatedList.push(input_value);
          // And resort alphabetically the array
          unassociatedList.sort();
          var unassociatedListIndex;
          // Create an array that will be used as data to be posted
          var addItem = {"value": input_value};
          $('#add_%id%_loader').show();
          $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: $.param(addItem),
            success: function(html) 
            {
              // Test what is returned is well the id of the new record inserted
              if(!isNaN(html))
              {
                // Depending of the cases the new option will be inserted:
                // - at first position
                // - as very first option
                // - after a previous option
                if(unassociatedList.length)
                {
                  if(unassociatedList.length > 1)
                  {
                    for (unassociatedListIndex in unassociatedList)
                    {
                      if(unassociatedList[unassociatedListIndex] == input_value)
                      {
                        if(unassociatedListIndex == 0)
                        {
                          $('#unassociated_%id% option:first').before('<option value='+html+'>'+input_value+'</option>');
                        }
                        else
                        {
                          $('#unassociated_%id% option:nth-child('+unassociatedListIndex+')').after('<option value='+html+'>'+input_value+'</option>');
                        }
                      }
                    }
                  }
                  else
                  {
                    $('#unassociated_%id%').append('<option value='+html+'>'+input_value+'</option>');
                  }
                  // After insertion of new option retrigger the eventual filter applied
                  search_list();
                  // Than remove value from add input
                  $('#add_%id%').val('');
                }
              }
              else
              {
                // If an error is encountered, display it
                // Usually, it is a duplicate error
                $('#%error_id%').find('li').text(html);
                $('#%error_id%').show();
              }
              $('#add_%id%_loader').hide();
            },
            error: function(xhr)
            {
              addError('Error!  Status = ' + xhr.status);
              $('#add_%id%_loader').hide();
            }
          });
        }
        return false;
      });
    });

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
    $error_id = 'error_'.$id;
    // The two select boxes
    $associatedWidget = new sfWidgetFormSelect(array('multiple' => true, 'choices' => $associated), array('size' => $size, 'class' => $this->getOption('class_select').'-selected'));
    $unassociatedWidget = new sfWidgetFormSelect(array('multiple' => true, 'choices' => $unassociated), array('size' => $size, 'class' => $this->getOption('class_select')));
    // The input box used to filter content of unassociated select box
    $filterWidget = new sfWidgetFormInputText(array(), array('class'=>$this->getOption('filter_class')));
    // Options to be passed to the renderTag of reset filter image
    $filterResetOptions = array('src' => '/images/remove.png',
                                'id' => 'filter_'.$id."_clear",
                                'class' => 'filter_clear',
                                'alt' => __('Reset filter'),
                                'title' => __('Reset filter')
                               );
    $addOptionHTML = '';
    // If add of a value in distant table is possible and activated
    if ($this->getOption('add_active'))
    {
      // Define the add value input box
      $addOptionWidget = new sfWidgetFormInputText(array(), array('id' => 'add_'.$id, 'class'=>$this->getOption('add_class')));
      $addOptionWidget->setLabel(__('Add new value:'));
      // Options to be passed to the renderTag of add option image
      $addImageOptions = array('src' => '/images/add_green.png',
                               'id' => 'add_'.$id.'_image',
                               'class' => 'add_option',
                               'alt' => __('Add value'),
                               'title' => __('Add value')
                              );
      // Options to be passed to the timer displayed while tryin' to insert a value
      $addTimerImageOptions = array('src' => '/images/loader.gif',
                                    'id' => 'add_'.$id.'_loader',
                                    'style' => 'display:none;'
                                   );
      $addLinkOptions = array('id'=> 'add_'.$id.'_link',
                              'class' => 'add_option',
                              'href' => url_for($this->getOption('add_url'))
                              );
      $addOptionHTML = $this->renderContentTag('label',$addOptionWidget->getLabel(), array('for'=>'add_'.$id)).$addOptionWidget->render('add_'.$name).$this->renderContentTag('a',$this->renderTag('img', $addImageOptions), $addLinkOptions).$this->renderTag('img', $addTimerImageOptions);
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
      '%add_option%'         => $addOptionHTML,
      '%error_id%'           => $error_id
    ));
  }

}