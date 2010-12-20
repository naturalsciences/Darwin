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

  $('#clear_cat_relation').click(function (event)
  {
    event.preventDefault();
    $('.search_item_name').html('');
    $('#searchCatalogue_item_ref').val('');
    $('.search_item_name').closest('tr').hide();
  });

});
</script>

<?php echo form_tag('catalogue/search'.( isset($is_choose) ? '?is_choose='.$is_choose : '') , array('class'=>'search_form','id'=>'catalogue_filter'));?>
<div class="container">
    <table class="search" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
      <thead>
        <tr>
          <?php if(isset($searchForm['code'])):?>
            <th><?php echo $searchForm['code']->renderLabel();?></th>
          <?php endif;?>
          <th><?php echo $searchForm['name']->renderLabel();?></th>
          <?php if(isset($searchForm['classification'])):?>
            <th><?php echo $searchForm['classification']->renderLabel();?></th>
          <?php endif;?>
          <th><?php echo $searchForm['level_ref']->renderLabel();?></th>
          <?php if(isset($searchForm['lower_bound']) && isset($searchForm['upper_bound'])):?>
            <th class="datesNum"><?php echo $searchForm['lower_bound']->renderLabel();?></th>
            <th class="datesNum"><?php echo $searchForm['upper_bound']->renderLabel();?></th>
          <?php endif;?>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php if(isset($searchForm['code'])):?>
            <td><?php echo $searchForm['code'];?></td>
          <?php endif;?>
          <td><?php echo $searchForm['name'];?><?php echo $searchForm->renderHiddenFields();?></td>
          <?php if(isset($searchForm['classification'])):?>
            <td><?php echo $searchForm['classification'];?></td>
          <?php endif;?>
          <td><?php echo $searchForm['level_ref'];?></td>
          <?php if(isset($searchForm['lower_bound']) && isset($searchForm['upper_bound'])):?>
            <td class="datesNum"><?php echo $searchForm['lower_bound'];?></td>
            <td class="datesNum"><?php echo $searchForm['upper_bound'];?></td>
          <?php endif;?>
          <td><input class="search_submit" type="submit" name="search" value="<?php echo __('Search');?>" /></td>
        </tr>
        <tr class="hidden">
          <td><?php echo $searchForm['relation'];?></td>
          <td <?php if(isset($searchForm['lower_bound'])) echo 'colspan="3"'; elseif(isset($searchForm['classification'])) echo 'colspan="3"';?>><span class="search_item_name"></span></td>
          <td class="widget_row_delete">
            <?php echo image_tag('remove.png', 'alt=Delete class=clear_relation id=clear_cat_relation'); ?>
            <?php if($sf_user->getHelpIcon()) : ?>          
            <div class="help_ico" alt="<?php echo $searchForm['relation']->renderHelp();?>"></div>
            <?php endif ; ?>
          </td>   
        </tr>
      </tbody>
    </table>
    <div class="search_results">
      <div class="search_results_content">
      </div>
    </div>
    <?php if( (isset($user_allowed) && $user_allowed) || ($sf_user->getDbUserType() >= Users::ENCODER) ): ?>
    <div class='new_link'><a <?php echo !(isset($is_choose) && $is_choose)?'':'target="_blank"';?> href="<?php echo url_for($searchForm['table']->getValue().'/new') ?>"><?php echo __('New Unit');?></a></div>
    <?php endif ; ?>
  </div>
</form>
