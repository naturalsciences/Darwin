<table class="catalogue_table<?php echo($sf_user->isA(Users::REGISTERED_USER)?'_view':'') ;?>">
  <thead>
    <tr>
      <th><?php echo __('Type');?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($types as $type => $items):?>
    <tr>
      <td>
	<?php echo __(ucfirst($type)); ?>
      </td>
      <td>
	  <table class="widget_sub_table" alt="<?php echo $type;?>">
	    <thead>
	      <tr>
		<th></th>
		<th><?php echo __('People');?></th>
		<th><?php echo __('Sub-Type');?></th>
		<th></th>
	      </tr>
	    </thead>
	    <tbody>
	    <?php foreach($items as $person):?>
	      <tr class="peo_id_<?php echo $person->getId();?>" id="id_<?php echo $person->getId();?>">
		<td class="handle"><?php echo image_tag('drag.png');?></td>
		<td>
		  <?php if($sf_user->isAtLeast(Users::ENCODER)) : ?>
		    <a class="link_catalogue" title="<?php echo __('Edit People');?>" href="<?php echo url_for('cataloguepeople/people?table='.$table.'&rid='.$eid.'&id='.$person->getId()); ?>">
		      <?php echo $person->People->getFormatedName();?>
		    </a>
		  <?php else : ?>
<a class="link_catalogue" title="<?php echo __('View People');?>" href="<?php echo url_for('people/view?id='.$person->getId()); ?>">		  
          <?php echo $person->People->getFormatedName();?>	  
        </a>
		  <?php endif ; ?>		  
		</td>
		<td class="catalogue_people_sub_type">
		   <?php echo $person->getPeopleSubType();?>
		</td>
		<td class="widget_row_delete">
    <?php if($sf_user->isAtLeast(Users::ENCODER)) : ?>		
		  <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=catalogue_people&id='.$person->getId());?>" title="<?php echo __('Are you sure ?') ?>">
		    <?php echo image_tag('remove.png'); ?>
		  </a>
		<?php endif ?>		  
		</td>
	      </tr>
	    <?php endforeach;?>
	    </tbody>
	  </table>
      </td>

    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<br />
<?php if($sf_user->isAtLeast(Users::ENCODER)) : ?>
<?php echo image_tag('add_green.png');?><a title="<?php echo __('Add People');?>" class="link_catalogue" href="<?php echo url_for('cataloguepeople/people?table='.$table.'&rid='.$eid); ?>"><?php echo __('Add');?></a>
<?php endif ; ?>
<script type="text/javascript">

function forceHelper(e,ui)
{
   $(".ui-state-highlight").html("<td colspan='3'>&nbsp;</td>");
}

$(document).ready(function()
{

  $("#cataloguePeople .widget_sub_table tbody").sortable({
      placeholder: 'ui-state-highlight',
      handle: '.handle',
      axis: 'y',
      change: function(e, ui) {
	forceHelper(e,ui);
      },
      deactivate: function(event, ui) {
	  el_Array = $(this).sortable('toArray');
	  result='';
	  for(item in el_Array)
	  {
	    result += getIdInClasses( $('#'+el_Array[item]) )+',';
	  }
	  $.ajax({
	    type: "POST",
	    url: "<?php echo url_for('cataloguepeople/editOrder?table='.$table.'&rid='.$eid); ?>",
	    data: { order: result, people_type: $(this).parent().attr('alt') }
	  });
      }
    });
});
</script>
