<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<?php echo form_tag('gtu/search'.( isset($is_choose) ? '?is_choose='.$is_choose : '') , array('class'=>'search_form','id'=>'gtu_filter'));?>
  <div class="container">
    <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
      <thead>
        <tr>  
        <tr>
          <th><?php echo $form['code']->renderLabel() ?></th>
          <th><?php echo $form['gtu_from_date']->renderLabel(); ?></th>
          <th><?php echo $form['gtu_to_date']->renderLabel(); ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $form['code']->render() ?></td>
          <td><?php echo $form['gtu_from_date']->render() ?></td>
          <td><?php echo $form['gtu_to_date']->render() ?></td>
          <td></td>
        </tr>
        <tr>
          <th colspan="4"><?php echo $form['tags']->renderLabel() ?></th>
        </tr>

        <?php echo include_partial('andSearch',array('form' => $form['Tags'][0]));?>

        <tr class="and_row">
          <td colspan="3"></td>
          <td>
            <?php echo image_tag('add_blue.png');?> <a href="<?php echo url_for('gtu/andSearch');?>" class="and_tag"><?php echo __('And'); ?></a>
          </td>
        </tr>
        <tr>
          <td colspan="3"></td>
          <td>
            <input class="search_submit" type="submit" name="search" value="<?php echo __('Search'); ?>" />
          </td>
        </tr>
      </tbody>
    </table>
    <script  type="text/javascript">
      var num_fld = 1;
      $('.and_tag').click(function()
      {
        $.ajax({
            type: "GET",
            url: $(this).attr('href') + '/num/' + (num_fld++) ,
            success: function(html)
            {
              $('table.search > tbody .and_row').before(html);
            }
          });
        return false;
      });

      $('.widget_row_delete').live('click',function(){
        if($('.tag_line').length == 1)
          $(this).closest('tr').find('.tag_line').val('');
        else
        $(this).closest('tr').remove();
      });

      $('input.tag_line').live('keydown click',purposeTags);

      function purposeTags(event)
      {
        if (event.type == 'keydown')
        {
          var code = (event.keyCode ? event.keyCode : event.which);
          if (code != 59 /* ;*/ && code != $.ui.keyCode.SPACE ) return;
        }        
        parent_el = $(this).closest('tr');

        if($(this).val() == '') return;
        $('.purposed_tags').html('<img src="/images/loader.gif" />');
        $.ajax({
          type: "GET",
          url: "<?php echo url_for('gtu/purposeTag');?>" + '/value/'+ $(this).val(),
          success: function(html)
          {
            parent_el.find('.purposed_tags').html(html);
            parent_el.find('.purposed_tags').show();
          }
        });
      }

      $('.purposed_tags li').live('click', function()
      {
        input_el = $(this).closest('tr').find('input.tag_line');
        if(input_el.val().match("\;\s*$"))
          input_el.val( input_el.val() + $(this).text() );
        else
          input_el.val( input_el.val() + " ; " +$(this).text() );
        input_el.trigger('click');
      });

    </script>
    <div class="search_results">
      <div class="search_results_content"> 
      </div>
    </div>
    <div class='new_link'><a <?php echo !(isset($is_choose) && $is_choose)?'':'target="_blank"';?> href="<?php echo url_for('gtu/new') ?>"><?php echo __('New');?></a></div>
  </div>
</form> 
