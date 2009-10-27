<?php slot('title', __('Search Taxonomic unit'));  ?>
<script type="text/javascript">
$(document).ready(function () {
    $("#search_taxa").submit(function ()
    {
      $(".search_result").html('Searching');
      $.ajax({
	type: "POST",
	url: "<?php echo url_for('taxonomy/search');?>",
	data: $('#search_taxa').serialize(),
	success: function(html){
	  $(".search_result ul").html(html);
	}});
	return false;
    });
});
</script>
<div class="page">
 <h2><?php echo __('Search');?></h2>
  <form id="search_taxa" method="post" action="">
    <table>
    <?php echo $searchForm;?>
    </table>
    <input type="submit" name="search" value="Search" />
  </form>

  <div class="search_result">
   <ul>
   </ul>
  </div>
</div>