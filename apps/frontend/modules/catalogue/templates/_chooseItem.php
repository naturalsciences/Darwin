<script type="text/javascript">
$(document).ready(function () {
      $("#search_form").submit(function ()
      {
	$(".tree").slideUp().html("");
	$.ajax({
	  type: "POST",
	  url: "<?php echo url_for('catalogue/search' . ($is_choose?'?is_choose=1' : '') );?>",
	  data: $('#search_form').serialize(),
	  success: function(html){
	    $('.search_results_content').html(html);
            $('.search_results').slideDown();
	  }});
        $(".search_results_content").html('<?php echo image_tag('loader.gif');?>');
        return false;
      });

      $('.search_results_content tbody tr .info').live('click',function() {
	  item_row=$(this).closest('tr');
	  if(item_row.find('.tree').is(":hidden"))
	  {
	    $.get('<?php echo url_for('catalogue/tree?table='.$searchForm['table']->getValue());?>/id/'+getIdInClasses(item_row),function (html){
	      item_row.find('.tree').html(html).slideDown();
	    });
	  }
	  $('.tree').slideUp();
      });

   $(".pager a").live('click',function ()
   {
     $.ajax({
             type: "POST",
             url: $(this).attr("href"),
             data: $('#search_form').serialize(),
             success: function(html){
                                     $(".search_results_content").html(html);
                                     $('.search_results').slideDown();
                                    }
            }
           );
     $(".search_results_content").html('<?php echo image_tag('loader.gif');?>');
     return false;
   });

});
</script>  
<form id="search_form" method="post" action="" class="search_form">
  <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
    <tbody>
      <tr>
        <td colspan="2"><?php echo $searchForm['table'];?></td>
      </tr>
      <tr>
        <td><?php echo $searchForm['name'];?></td>
        <td><input class="search_submit" type="submit" name="search" value="<?php echo __('Search');?>" /></td>
      </tr>
    </tbody>
  </table>
<div class="search_results">
  <div class="search_results_content">
  </div>
</div>
</form>
