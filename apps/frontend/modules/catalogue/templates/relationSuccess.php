<div id="renamed_screen">
  <div class="catalogue_ref"><?php echo $linkItem->getName();?></div>
  <?php if($sf_params->get('type')=='rename'):?>
    <label class="catalogue_action_type"><?php echo __('is renamed in');?> :</label>
  <?php else:?>
    <label class="catalogue_action_type"><?php echo __('is recombined from');?> :</label>
  <?php endif;?>
  <form method="post" action="<?php echo url_for('catalogue/SaveRelation?type='.($sf_params->get('type')=='rename'? 'rename': 'recombined') .'&table='.$sf_params->get('table').'&id='.$linkItem->getId());?>" class="renamed">
  <table class="bottom_actions">
    <tbody>
      <tr>
        <td>
          <input type="hidden" name="record_id_2" id="relation_catalogue_id" value="<?php echo $relation->getRecordId2(); ?>" />
        </td>
      </tr>
      <tr>
        <td>
          <input type="hidden" name="relation_id" value="<?php echo $relation->getId(); ?>"/>
        </td>
      </tr>
      <tr>
        <td>
          <div id="relation_catalogue_name" <?php if($remoteItem->isNew()):?> class="hidden"> <?php else: ?>> <?php echo $remoteItem->getNameWithFormat();?><?php endif;?></div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="clear"> </div>
        </td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td>
          <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
          <?php if(! $remoteItem->isNew()):?>
	    <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=catalogue_relationships&id='.$relation->getId());?>" title="<?php echo __('Are you sure ?') ?>">
	      <?php echo __('Delete');?>
	    </a>
	  <?php endif;?>
          <input id="save" class="save" type="submit" name="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
  </form>

 <script type="text/javascript">
  $(document).ready(function () 
  {
    $('.result_choose').live('click',function () {
	el = $(this).closest('tr');
	$("#relation_catalogue_id").val(getIdInClasses(el));
	$("#relation_catalogue_name").text(el.find('span.item_name').text()).show();
    });

    $('form.renamed').submit(function () {
      $('form.renamed input[type=submit]').attr('disabled','disabled');
      hideForRefresh($('#renamed_screen'));
      $.ajax({
          type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
            if(html == 'ok')
            {
              $('.qtip-button').click();
            }
            $('form.renamed').parent().before(html).remove();
	  }});
	return false;
      });
  }); 
  </script>
  <div class="search_box show">
    <?php include_partial('catalogue/chooseItem', array('searchForm' => $searchForm,'is_choose'=>true)) ?>
  </div>
</div>
