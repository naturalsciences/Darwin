
<script type="text/javascript">
$(document).ready(function () {
      $("#people_filter").submit(function ()
      {
	$.ajax({
	  type: "POST",
	  url: "<?php echo url_for('people/search' . ($is_choose?'?is_choose=1' : '') );?>",
	  data: $('#people_filter').serialize(),
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
             data: $('#people_filter').serialize(),
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
<form id="people_filter" class="search_form" method="post" action="<?php echo url_for('people/search'.((!isset($is_choose))?'':'?is_choose='.$is_choose));?>">
  <div class="container">
    <?php echo $form['is_physical'];?>
    <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
      <thead>
        <tr>
          <th><?php echo $form['family_name']->renderLabel('Name') ?></th>
          <th><?php echo $form['activity_date_from']->renderLabel(); ?></th>
          <th><?php echo $form['activity_date_to']->renderLabel(); ?></th>
	  <th><?php echo $form['db_people_type']->renderLabel('Type');?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $form['family_name']->render() ?></td>
          <td><?php echo $form['activity_date_from']->render() ?></td>
          <td><?php echo $form['activity_date_to']->render() ?></td>
          <td><?php echo $form['db_people_type']->render() ?></td>
          <td><input class="search_submit" type="submit" name="search" value="<?php echo __('Search'); ?>" /></td>
        </tr>
      </tbody>
    </table>
    <div class="search_results">
      <div class="search_results_content"> 
      </div>
    </div> 
    <div class='new_link'><a href="<?php echo url_for('people/new') ?>"><?php echo __('New');?></a></div>
  </div>
</form> 