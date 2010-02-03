
<script type="text/javascript">
$(document).ready(function () {
      $("#institution_filter").submit(function ()
      {
	$.ajax({
	  type: "POST",
	  url: "<?php echo url_for('institution/search' . ($is_choose?'?is_choose=1' : '') );?>",
	  data: $('#institution_filter').serialize(),
	  success: function(html){
	    $('.search_results_content').html(html);
            $('.search_results').slideDown();
	  }});
        $(".search_results_content").html('<?php echo image_tag('loader.gif');?>');
        return false;
      });

   $(".pager a").live('click',function ()
   {
     $.ajax({
             type: "POST",
             url: $(this).attr("href"),
             data: $('#institution_filter').serialize(),
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

<form id="institution_filter" class="search_form" method="post" action="<?php echo url_for('institution/search'.((!isset($is_choose))?'':'?is_choose='.$is_choose));?>">
  <?php echo $form->renderGlobalErrors() ?>
  <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
    <thead>
      <tr>  
        <th><?php echo $form['family_name']->renderLabel(__('Name'));?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $form['family_name'];?><?php echo $form['is_physical'];?></td>
        <td><input class="search_submit" type="submit" name="search" value="<?php echo __('Search'); ?>" /></td>
      </tr>
    </tbody>
  </table>
<div class="search_results">
  <div class="search_results_content"> 
  </div>
</div>
</form> 