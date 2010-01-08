<script type="text/javascript">
$(document).ready(function () {
      $("#search_catalogue_form").submit(function ()
      {
	$(".search_content").html('Searching');
	$(".tree").slideUp().html("");
	$.ajax({
	  type: "POST",
	  url: "<?php echo url_for('catalogue/search' . ($is_choose?'?is_choose=1' : '') );?>",
	  data: $('#search_catalogue_form').serialize(),
	  success: function(html){
	    $('.search_content').html(html).slideDown();
	  }});
	  return false;
      });

      $('.search_content tbody tr .info').live('click',function() {
	  item_row=$(this).closest('tr');
	  if(item_row.find('.tree').is(":hidden"))
	  {
	    $.get('<?php echo url_for('catalogue/tree?table='.$searchForm['table']->getValue());?>/id/'+getIdInClasses(item_row),function (html){
	      item_row.find('.tree').html(html).slideDown();
	    });
	  }
	  $('.tree').slideUp();
      });
});
</script>  
<form id="search_catalogue_form" method="post" action="">
    <?php echo $searchForm['table'];?>
    <?php echo $searchForm['name'];?>
    <input type="submit" name="search" value="<?php echo __('Search');?>" />
  </form>

<div class="search_content">
</div>