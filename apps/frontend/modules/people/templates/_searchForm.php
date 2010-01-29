
<script type="text/javascript">
$(document).ready(function () {
      $("#people_filter").submit(function ()
      {
	$(".search_content").html('Searching');
	$.ajax({
	  type: "POST",
	  url: "<?php echo url_for('people/search' . ($is_choose?'?is_choose=1' : '') );?>",
	  data: $('#people_filter').serialize(),
	  success: function(html){
	    $('.search_content').html(html).slideDown();
	  }});
	  return false;
      });

   $(".pager a").live('click',function ()
   {
     $.ajax({
             type: "POST",
             url: $(this).attr("href"),
             data: $('#people_filter').serialize(),
             success: function(html){
                                     $(".search_content").html(html);
                                    }
            }
           );
     $(".search_content").html('<?php echo image_tag('loader.gif');?>');
     return false;
   });
});
</script>  
<form id="people_filter" class="search_form" method="post" action="<?php echo url_for('people/search'.((!isset($is_choose))?'':'?is_choose='.$is_choose));?>">
  <?php echo $form->renderGlobalErrors(); ?>
  <?php echo $form['is_physical'];?>

  <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
    <thead>
      <tr>
        <th><?php echo $form['family_name']->renderLabel('Name') ?></th>
        <th><?php echo $form['activity_date_from']->renderLabel(); ?></th>
        <th><?php echo $form['activity_date_to']->renderLabel(); ?></th>
	<th><?php echo $form['db_people_type']->renderLabel('Type');?></th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <?php echo $form['family_name']->renderError() ?>
        </td>
        <td>
          <?php echo $form['activity_date_from']->renderError() ?>
        </td>
        <td>
          <?php echo $form['activity_date_to']->renderError() ?>
        </td>
	<td>
          <?php echo $form['db_people_type']->renderError() ?>
        </td>
        <td>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo $form['family_name']->render() ?>
        </td>
        <td>
          <?php echo $form['activity_date_from']->render() ?>
        </td>
        <td>
          <?php echo $form['activity_date_to']->render() ?>
        </td>
        <td>
          <?php echo $form['db_people_type']->render() ?>
        </td>
        <td>
          <input id="search_submit" type="submit" name="search" value="<?php echo __('Search'); ?>" />
        </td>
      </tr>
    </tbody>
  </table>

<div class="search_content"> 
</div> 
</form> 