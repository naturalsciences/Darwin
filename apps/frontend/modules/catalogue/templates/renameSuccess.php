<div class="catalogue_ref"><?php echo $linkItem->getName();?></div><label class="catalogue_action_type">is renamed in :</label>
<form method="post" action="<?php echo url_for('catalogue/SaveRelation?type=current_name&table='.$sf_params->get('table').'&id='.$linkItem->getId());?>" id="renamed">
   <input type="hidden" name="record_id_2" id="relation_catalogue_id" />
   <input type="hidden" name="relation_id" value="<?php echo $relation->getId(); ?>"/>
   <div id="relation_catalogue_name" <?php if($renamedItem->getId()==0):?> class="hidden"> <?php else: ?>> <?php echo $renamedItem->getName();?><?php endif;?></div>
    <div class="clear"> </div>
  <button id="modify"><?php echo __('Modify');?></button>
  <button id="delete" <?php if($renamedItem->getId()==0):?> style="display:none" <?php endif;?>><?php echo __('Delete');?></button>
  <input style="display:none" type="submit" name="submit" id="save" value="<?php echo __('Save');?>" />
</form>

<br /><br />
  <script type="text/javascript">
  $(document).ready(function () {
    function closeRefresh()
    {
        widget = $('#relationRename');
        widget.find('.widget_content').load(reload_url+'/widget/'+widget.attr('id'));
	$('.qtip-button').click();
    }

    $('#choose_taxa_button').click(function () {
	ref_element_id = $(this).data('taxa_id');
	$("#relation_catalogue_name").text($(this).data('taxa_name')).show();
	$("#relation_catalogue_id").val($(this).data('taxa_id'));
        //$('.qtip-button').click();
    });

      $("#renamed").submit(function()
      {
	$.ajax({
	  type: "POST",
	  url: $('#renamed').attr('action'),
	  data: $('#renamed').serialize(),
	  success: function(html){
	    if(html == "ok" )
	    {
	     closeRefresh();
	    }
	    else
	    {
	      alert(html);
	    }
	  }});
	return false;
      });

      $("#delete").click(function()
      {
	$.ajax({
	  url: '<?php echo url_for('catalogue/deleteRelation?relid='.$relation->getId())?>',
	  success: function(html){
	    if(html == "ok" )
	    {
	      closeRefresh();
	    }
	    else
	    {
	      alert(html);
	    }
	  }});
	return false;
      });

      $("#modify").click(function()
      {
	$(this).hide();
	$("#catalogue_search").show();
	$("#save").show();
	$("#searchTaxon_name").val($("#catalogue_relationships_record_id_2").val());
	return false;
      });

      $("#search_catalogue").submit(function ()
      {
	$(".search_content").html('Searching');
	$(".tree").slideUp();
	$(".tree_content").html("");
	$.ajax({
	  type: "POST",
	  url: "<?php echo url_for('catalogue/search');?>",
	  data: $('#search_catalogue').serialize(),
	  success: function(html){
	    $(".search_content").html(html);
	    $(".search h3").show();
	    $('.search').slideDown();
	  }});
	  return false;
      });

      $('.search_content ul li').live('click',function() {
	  $('.tree').slideUp();
	  $('#choose_taxa_button').data('taxa_id',getIdInClasses($(this)));
	  $('#choose_taxa_button').data('taxa_name',$(this).text());
	  $.get('<?php echo url_for('taxonomy/tree?table='.$searchForm['table']->getValue());?>/id/'+getIdInClasses($(this)),function (html){
	    $('.tree_content').html(html);
	    $('.tree').slideDown();
	  });
      });
      
  }); 
  
  </script>
<div id="catalogue_search" style="display:none;border: 2px solid #5BAABD;padding:1em;margin:1em;">
  <form id="search_catalogue" method="post" action="">
    <?php echo $searchForm['table'];?>
    <label>I Look for :</label>
    <?php echo $searchForm['name'];?>
    <input type="submit" name="search" value="<?php echo __('Search');?>" />
  </form>


    <div class="tree">
	<h3><?php echo __('Details :');?></h3>
	<div class="tree_content">
	</div>
	<input type="button" id="choose_taxa_button" value="<?php echo __('Select');?>">
    </div>

  <div class="search">
    <h3><?php echo __('Search Results :');?></h3>
    <div class="search_content">
    </div>
  </div>
</div>