<?php include_stylesheets_for_form($searchForm) ?>
<?php include_javascripts_for_form($searchForm) ?>

<script type="text/javascript">
$(document).ready(function () 
{
   $('.search_results_content tbody tr .info').live('click',function() 
   {
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
<form id="catalogue_filter" class="search_form" method="post" action="<?php echo url_for('catalogue/search'.((!isset($is_choose))?'':'?is_choose='.$is_choose));?>">
  <div class="container">
    <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
      <thead>
        <tr>  
          <th><?php echo $searchForm['name']->renderLabel('Name');?></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $searchForm['name'];?><?php echo $searchForm['table'];?></td>
          <td><input class="search_submit" type="submit" name="search" value="<?php echo __('Search');?>" /></td>
        </tr>
      </tbody>
    </table>
    <div class="search_results">
      <div class="search_results_content">
      </div>
    </div>
    <div class='new_link'><a href="<?php echo url_for($searchForm['table']->getValue().'/new') ?>"><?php echo __('New Unit');?></a></div>
  </div>
</form>
