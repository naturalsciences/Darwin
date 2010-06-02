<?php slot('title', __('Parts overview'));  ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'individual'=> $individual, 'mode' => 'parts_overview') );?>

<table class="catalogue_table">
  <thead>
	<tr>
	  <th></th>
	  <th><?php echo __('Code');?></th>
	  <th><?php echo __('Part');?></th>
	  <th><?php echo __('Room');?></th>
	  <th><?php echo __('Row');?></th>
	  <th><?php echo __('Shelf');?></th>
	  <th><?php echo __('Container');?></th>
	  <th><?php echo __('Sub container');?></th>
	  <th></th>
	  <th></th>
	</tr>
  </thead>
  <tbody>
  <?php foreach($parts as $part):?>
	<tr class="part_id_<?php echo $part->getId();?>">
      <td class="info_cell">
	  <?php echo image_tag('info-green.png',"title=info class=extd_info");?>
		<div class="extended_info" style="display:none;">
		  <?php include_partial('extendedInfo', array('part' => $part, 'codes' => $codes) );?>
		</div>
	  </td>
	  <td><?php if(isset($codes[$part->getId()])):?>
		  <ul><?php foreach($codes[$part->getId()] as $code):?>
			<li><?php echo $code->getCodeFormated();?></li>
		  <?php endforeach;?></ul>
		<?php endif;?>
	  </td>
	  <td><?php echo $part->getSpecimenPart();?></td>
	  <td><?php echo $part->getRoom();?></td>
	  <td><?php echo $part->getRow();?></td>
	  <td><?php echo $part->getShelf();?></td>
	  <td><?php echo $part->getContainer();?></td>
	  <td><?php echo $part->getSubContainer();?></td>
	  <td>
		<?php echo link_to(image_tag('edit.png'),'parts/edit?id='.$part->getId());?>
	  </td>
	  <td>
		<a class="row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=specimen_parts&id='.$part->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
	  </td>
	</tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='10'>
        <div class="add_spec_individual">
		  <a href="<?php echo url_for('parts/edit?indid='.$individual->getId());?>"><?php echo __('Add part');?></a>
        </div>
      </td>
    </tr>
  </tfoot>
</table>

<br />
<script  type="text/javascript">
$(document).ready(function () {
  $("a.row_delete").click(function(){

	if(confirm($(this).attr('title')))
	{
	  currentElement = $(this);
	  $.ajax({
		url: $(this).attr('href'),
        success: function(html) {
		  if(html == "ok" )
		  {
			currentElement.closest('tr').remove();
		  }
		  else
		  {
			//addError(html, currentElement); //@TODO:change this!
			alert('Error: ' + html);
		  }
		},
		error: function(xhr){
		 // addError('Error!  Status = ' + xhr.status);
		 alert('Error!  Status = ' + xhr.status);
		}
	  });
	}
    return false;
  });


  $('img.extd_info').each(function(){
	   
	tip_content = $(this).next().html();
	$(this).qtip(
	{
         content: tip_content,
         style: {
            tip: true, // Give it a speech bubble tip with automatic corner detection
            name: 'cream'
         }
      });
    });
});
</script>

<?php include_partial('specimen/specAfterTab');?>
