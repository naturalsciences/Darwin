<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<script type="text/javascript">
$(document).ready(function () 
 {
   $("#search_form").submit(function ()
   {
     $.ajax({
             type: "POST",
             url: $(this).attr('action'),
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

   $("a.sort").live('click',function ()
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
<form id="search_form" action="<?php echo url_for('igs/search'.((!isset($is_choose))?'':'?is_choose='.$is_choose));?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <div class="container">
    <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
      <thead>
        <tr>
          <th><?php echo $form['ig_num']->renderLabel() ?></th>
          <th><?php echo $form['from_date']->renderLabel(); ?></th>
          <th><?php echo $form['to_date']->renderLabel(); ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $form['ig_num']->render() ?></td>
          <td><?php echo $form['from_date']->render() ?></td>
          <td><?php echo $form['to_date']->render() ?></td>
          <td><input class="search_submit" type="submit" name="search" value="<?php echo __('Search'); ?>" /></td>
        </tr>
      </tbody>
    </table>
    <div class="search_results">
      <div class="search_results_content">
      </div>
    </div>
    <div class='new_link'><a href="<?php echo url_for('igs/new') ?>"><?php echo __('New');?></a></div>
  </div>
</form>
