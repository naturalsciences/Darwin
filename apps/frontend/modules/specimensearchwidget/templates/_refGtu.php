  <div class="container">
    <table class="search" id="search">
      <thead>
        <tr>  
        <tr>
          <th><?php echo $form['gtu_code']->renderLabel() ?></th>
          <th><?php echo $form['gtu_from_date']->renderLabel(); ?></th>
          <th><?php echo $form['gtu_to_date']->renderLabel(); ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $form['gtu_code']->render() ?></td>
          <td><?php echo $form['gtu_from_date']->render() ?></td>
          <td><?php echo $form['gtu_to_date']->render() ?></td>
	  <td></td>
	</tr>
	<tr>
          <th colspan="4"><?php echo $form['tags']->renderLabel() ?></th>
	</tr>
  <?php foreach($form['Tags'] as $i=>$form_value):?>
    <?php include_partial('specimensearch/andSearch',array('form' => $form['Tags'][$i]));?>    
  <?php endforeach;?>


	<tr class="and_row">
	  <td colspan="3"></td>
          <td>
	    <?php echo image_tag('add_blue.png');?> <a href="<?php echo url_for('specimensearch/andSearch');?>" class="and_tag"><?php echo __('And'); ?></a>
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
  </script>
</div>
