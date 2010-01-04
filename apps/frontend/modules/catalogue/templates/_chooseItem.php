<script type="text/javascript">
$(document).ready(function () {
      $("#search_catalogue_form").submit(function ()
      {
	$(".search_content").html('Searching');
	$(".tree").slideUp();
	$(".tree_content").html("");
	$.ajax({
	  type: "POST",
	  url: "<?php echo url_for('catalogue/search');?>",
	  data: $('#search_catalogue_form').serialize(),
	  success: function(html){
	    $(".search_content").html(html);
	    $(".search_catalogue_result h3").show();
	    $('.search_catalogue_result').slideDown();
	  }});
	  return false;
      });

      $('.search_content ul li').live('click',function() {
	  $('.tree').slideUp();
	  $('#choose_taxa_button').data('taxa_id',getIdInClasses($(this)));
	  $('#choose_taxa_button').data('taxa_name',$(this).text());
	  $.get('<?php echo url_for('catalogue/tree?table='.$searchForm['table']->getValue());?>/id/'+getIdInClasses($(this)),function (html){
	    $('.tree_content').html(html);
	    $('.tree').slideDown();
	  });
      });
});
</script>  
<form id="search_catalogue_form" method="post" action="">
    <?php echo $searchForm['table'];?>
    <?php echo $searchForm['name'];?>
    <input type="submit" name="search" value="<?php echo __('Search');?>" />
  </form>


    <div class="tree">
	<h3><?php echo __('Details :');?></h3>
	<div class="tree_content">
	</div>
	<input type="button" id="choose_taxa_button" value="<?php echo __('Select');?>">
    </div>

  <div class="search_catalogue_result">
    <h3><?php echo __('Search Results');?></h3>
    <div class="search_content">
    </div>
  </div>