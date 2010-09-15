  <div class="container">
    <table id="gtu_search">
      <thead>
        <tr>
          <th colspan="4"><?php echo $form['gtu_code']->renderLabel() ?></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="4"><?php echo $form['gtu_code']->render() ?></td>
        </tr>
        <tr>
          <th><?php echo $form['gtu_from_date']->renderLabel(); ?></th>
          <th><?php echo $form['gtu_to_date']->renderLabel(); ?></th>
          <th colspan="2"></th>
        </tr>
        <tr>
          <td><?php echo $form['gtu_from_date']->render() ?></td>
          <td><?php echo $form['gtu_to_date']->render() ?></td>
          <td colspan="2"></td>
        </tr>
        <tr>
          <th colspan="2"><?php echo $form['tags']->renderLabel() ?></th>
          <th colspan="2"></th>
        </tr>
        <?php foreach($form['Tags'] as $i=>$form_value):?>
          <?php include_partial('specimensearch/andSearch',array('form' => $form['Tags'][$i], 'row_line'=>$i));?>
        <?php endforeach;?>
        <tr class="and_row">
          <td colspan="3"></td>
          <td><?php echo image_tag('add_blue.png');?><a href="<?php echo url_for('specimensearch/andSearch');?>" class="and_tag"><?php echo __('And'); ?></a></td>
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
            $('table#gtu_search > tbody .and_row').before(html);
          }
        });
        return false;
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
  </div>
