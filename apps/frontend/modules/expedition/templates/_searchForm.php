<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<script type="text/javascript">
$(document).ready(function () 
 {
   $("#search_expedition").submit(function ()
   {
     $(".search_content").html('<?php echo image_tag('loader.gif');?>');
     $.ajax({
             type: "POST",
             url: $(this).attr('action'),
             data: $('#search_expedition').serialize(),
             success: function(html){
                                     $(".search_results_content").html(html);
                                     $('.search_results').slideDown();
                                    }
            }
           );
     return false;
   });

   $("a.sort").live('click',function ()
   {
     $.ajax({
             type: "POST",
             url: $(this).attr("href"),
             data: $('#search_expedition').serialize(),
             success: function(html){
                                     $(".search_results_content").html(html);
                                    }
            }
           );
     $(".search_content").html('<?php echo image_tag('loader.gif');?>');
     return false;
   });

   $(".pager a").live('click',function ()
   {
     $.ajax({
             type: "POST",
             url: $(this).attr("href"),
             data: $('#search_expedition').serialize(),
             success: function(html){
                                     $(".search_results_content").html(html);
                                    }
            }
           );
     $(".search_content").html('<?php echo image_tag('loader.gif');?>');
     return false;
   });

 });
</script>
<form id="search_expedition" action="<?php echo url_for('expedition/search'.((!isset($is_choose))?'':'?is_choose='.$is_choose));?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <?php echo $form->renderGlobalErrors() ?>
  <table class="search">
    <thead>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <th><?php echo $form['from_date']->renderLabel(); ?></th>
        <th><?php echo $form['to_date']->renderLabel(); ?></th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <?php echo $form['name']->renderError() ?>
        </td>
        <td>
          <?php echo $form['from_date']->renderError() ?>
        </td>
        <td>
          <?php echo $form['to_date']->renderError() ?>
        </td>
        <td>
        </td>
      </tr>
      <tr>
        <td>
          <?php echo $form['name']->render() ?>
        </td>
        <td>
          <?php echo $form['from_date']->render() ?>
        </td>
        <td>
          <?php echo $form['to_date']->render() ?>
        </td>
        <td>
          <input type="submit" name="search" value="<?php echo __('Search'); ?>" />
        </td>
      </tr>
    </tbody>
  </table>
  <br /><br />
  <div class="search_results">
    <div class="search_results_content">
    </div>
  </div>
</form>
