<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<script type="text/javascript">
$(document).ready(function () {
    $('form.qtiped_form').modal_screen();

    $('.treelist li:not(li:has(ul)) img.tree_cmd').hide();
    $('.collapsed').click(function()
    {
        $(this).hide();
        $(this).siblings('.expanded').show();
        $(this).parent().siblings('ul').show();
    });
    
    $('.expanded').click(function()
    {
        $(this).hide();
        $(this).siblings('.collapsed').show();
        $(this).parent().siblings('ul').hide();
    });
    $('.treelist li input[type=checkbox]').click(function()
    {
	  class_val = $(this).closest('li').attr('class');
   	  val = $(this).attr('checked') ;
	  alt_val = $(this).closest('ul .'+class_val).find(':checkbox').attr('checked',val);
//	  $('tbody[alt="'+alt_val+'"] tr input[value="'+$(this).val()+'"]').attr("checked","checked");
    		
    });
});
</script>
<?php echo form_tag('collection/rights?user_ref='.$sf_params->get('user_ref').'&collection_ref='.$sf_params->get('collection_ref'), array('class'=>'edition qtiped_form', 'id' => 'collection_right_form') );?>
  <table class="widget_sub_table">
    <tr>
      <td>
        <div class="treelist">
          <?php echo $form['CollectionsRights']; ?>
       </div>
	    </td>
	  </tr>
  </table>     
</form>
