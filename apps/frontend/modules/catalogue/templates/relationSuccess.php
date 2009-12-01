<ul class="hidden error_list">
  <li></li>
</ul>
<div class="catalogue_ref"><?php echo $linkItem->getName();?></div>
<?php if($sf_params->get('type')=='rename'):?>
  <label class="catalogue_action_type"><?php echo __('is renamed in');?> :</label>
<?php else:?>
  <label class="catalogue_action_type"><?php echo __('is recombined into');?> :</label>
<?php endif;?>
<form method="post" action="<?php echo url_for('catalogue/SaveRelation?type='.($sf_params->get('type')=='rename'? 'rename': 'recombined') .'&table='.$sf_params->get('table').'&id='.$linkItem->getId());?>" id="renamed">
   <input type="hidden" name="record_id_2" id="relation_catalogue_id" value="<?php echo $relation->getRecordId2(); ?>" />
   <input type="hidden" name="relation_id" value="<?php echo $relation->getId(); ?>"/>
   <div id="relation_catalogue_name" <?php if($remoteItem->getId()==0):?> class="hidden"> <?php else: ?>> <?php echo $remoteItem->getName();?><?php endif;?></div>
    <div class="clear"> </div>
  <button id="modify"><?php echo __('Modify');?></button>
  <button id="delete" <?php if($remoteItem->getId()==0):?> style="display:none" <?php endif;?>><?php echo __('Delete');?></button>
  <input class="hidden" type="submit" name="submit" id="save" value="<?php echo __('Save');?>" />
</form>

 <script type="text/javascript">
  $(document).ready(function () {
    $('#choose_taxa_button').click(function () {
	ref_element_id = $(this).data('taxa_id');
	$("#relation_catalogue_name").text($(this).data('taxa_name')).show();
	$("#relation_catalogue_id").val($(this).data('taxa_id'));
    });
  
    function addError(html)
    {
      $('.error_list li').text(html);
      $('.error_list').show();
    }
    function removeError()
    {
	$('.error_list').hide();
	$('.error_list li').text(' ');
    }
      $("#renamed").submit(function()
      {
	removeError();
	$.ajax({
	  type: "POST",
	  url: $('#renamed').attr('action'),
	  data: $('#renamed').serialize(),
	  success: function(html){
	    if(html == "ok" )
	    {
	     $('.qtip-button').click();
	    }
	    else
	    {
	      addError(html);
	    }
	  },
	  error: function(xhr)
	  {
	    addError('Error!  Status = ' + xhr.status);
	  }});
	return false;
      });

      $("#delete").click(function()
      {
	removeError();
	$.ajax({
	  url: '<?php echo url_for('catalogue/deleteRelation?relid='.$relation->getId())?>',
	  success: function(html){
	    if(html == "ok" )
	    {
	      $('.qtip-button').click();
	    }
	    else
	    {
	      addError(html);
	    }
	  },
	  error: function(xhr)
	  {
	    addError('Error!  Status = ' + xhr.status);
	  }});
	return false;
      });

      $("#modify").click(function()
      {
	$(this).hide();
	$("#search_box").show();
	$("#save").show();
	return false;
      });      
  }); 
  </script>
<div id="search_box">
  <?php include_partial('catalogue/chooseItem', array('searchForm' => $searchForm)) ?>
</div>