<?php slot('title', __('Search Taxonomic unit'));  ?>
<script type="text/javascript">
$(document).ready(function () {
    $("#search_taxa").submit(function ()
    {
      $(".search_content").html('Searching');
      $(".tree").slideUp();
      $(".tree_content").html("");
      $.ajax({
	type: "POST",
	url: "<?php echo url_for('catalogue/search');?>",
	data: $('#search_taxa').serialize(),
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
	$.get('<?php echo url_for('catalogue/tree?table=taxonomy');?>/id/'+getIdInClasses($(this)),function (html){
	  $('.tree_content').html(html);
	  $('.tree').slideDown();
	});
    });
}); 

</script>
  <form id="search_taxa" method="post" action="">
    <table>
    <?php echo $searchForm;?>
    </table>
    <input type="hidden" name="searchTaxon[table]" value="taxonomy" />
    <input type="submit" name="search" value="<?php echo __('Search');?>" />
  </form>

  <div class="tree">
      <h3><?php echo __('Details :');?></h3>
      <div class="tree_content">
      </div>
      <?php if($is_choose):?>
	<input type="button" id="choose_taxa_button" value="<?php echo __('Select');?>">
      <?php endif;?>
  </div>

  <div class="search">
      <h3><?php echo __('Search Results :');?></h3>
      <div class="search_content">
      </div>
  </div>
