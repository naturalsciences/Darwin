<script type="text/javascript">
$(document).ready(function () {
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
    });    
    $('#clear_collections').click(function()
    {
  	  $('table.widget_sub_table').find(':checkbox').attr('checked','');    
    });
    
});
</script>
<table class="widget_sub_table">
  <tr>
    <td>
      <div class="treelist">
		    <?php echo $form['collection_ref'] ; ?>        
      </div>
	  </td>
	</tr>
</table>

