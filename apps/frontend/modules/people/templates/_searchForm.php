
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
  <?php echo $form->renderGlobalErrors() ?>

    <?php echo $form['family_name']->renderLabel('Name');?>
    <?php echo $form['family_name']->renderError(); ?>
    <?php echo $form['family_name'];?>
    <input type="submit" name="search" value="Search" />

<div class="search_content"> 
</div> 
</form> 