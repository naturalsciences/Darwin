<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
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
//	  $('tbody[alt="'+alt_val+'"] tr input[value="'+$(this).val()+'"]').attr("checked","checked");
    		
    });
});
</script>
<form id="collection_right_form" class="edition qtiped_form" action="<?php echo url_for('collection/rights?user_ref='.$sf_request->getParameter('user_ref').'&collection_ref='.$sf_request->getParameter('collection_ref')); ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<table class='widget_sub_table'>
<thead>
	<tr><td>
		<?php echo ('<h1  class="edit_mode">Browse Sub Collections for '.$user_formated_name.'</h1>');  ?>
	</td></tr>
<tbody>
<tr><td>
	<div class="treelist">
	<?php if (count($sub_coll) == 0) : ?>
		<?php echo ("<h2>No existing sub collections</h2>"); ?>
     <?php else : ?>
	<?php $prev_level = 0;?>
	<?php foreach($form['SubCollectionsRights'] as $key => $form_value):?>
	   <?php if($prev_level < $sub_coll[$key]['level']):?>
		<ul class="rid_<?php echo $sub_coll[$key]['level'] ;?>">
	   <?php else:?>
		</li>
 	   <?php if($prev_level > $sub_coll[$key]['level']):?>
		    <?php echo str_repeat('</ul></li>',$prev_level-$sub_coll[$key]['level']);?>
		  <?php endif;?>
	   <?php endif;?>
	     <li class="rid_<?php echo $sub_coll[$key]['level'] ;?>">
	     <div class="col_name">
      	   <?php echo image_tag ('individual_expand.png', array('alt' => '+', 'class'=> 'tree_cmd collapsed'));?>
	        <?php echo image_tag ('individual_expand_up.png', array('alt' => '-', 'class'=> 'tree_cmd expanded'));?>
		<span><?php echo $form_value['collection_ref']->renderLabel() ;?>
		      <?php echo $form_value->renderHiddenFields(); ?>
			 <div class="check_right">
				<?php echo $form_value['check_right']->renderError() ; ?>
				<?php echo $form_value['check_right'] ; ?>
		      </div>
		</span>	   
	   </div>
	   <?php $prev_level =$sub_coll[$key]['level'];?>
	 <?php endforeach;?>
   	 <?php echo str_repeat('</li></ul>',$sub_coll[$key]['level']-1);?>
   	 <?php endif ; ?>
    </div>
</td></tr>   
</tbody>
<tfoot>
<tr>
 <td>
  	<a class="cancel_qtip" href="#"><?php echo __('Close'); ?></a>
	<?php if (count($sub_coll) > 0) : ?>
 	<input id="reset" type="reset" value="Reset" />
 	<input id="submit" type="submit" value="<?php echo __('Save');?>" />
 	<?php endif ?>
 </td>
<tr>
</tfoot>
</table>
</form>
