<h3>Parts</h3>
<table class="results">
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
	</tr>
  </thead>
  <tbody>
  <?php foreach($parts as $part):?>
	<tr>
    <td>
	    <?php echo image_tag('info.png',"title=info class=extd_info");?>
		<div class="extended_info" style="display:none;">
		  <?php include_partial('parts/extendedInfo', array('part' => $part, 'codes' => $codes) );?>
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
		<?php echo link_to(image_tag('edit.png', array("title" => __("Edit"))),'parts/edit?id='.$part->getId(), array('title'=>__('Edit this part')));?>
		<?php echo link_to(image_tag('duplicate.png', array("title" => __("Duplicate this part"))),'parts/edit?indid='.$part->getSpecimenIndividualRef().
		'&duplicate_id='.$part->getId(), array('title'=>__('Duplicate this part')));?>
	  </td>
	</tr>
  <?php endforeach;?>
  </tbody>
</table>
<script  type="text/javascript">

function addError(html)
{
  $('ul#error_list').find('li').text(html);
  $('ul#error_list').show();
}

function removeError()
{
  $('ul#error_list').hide();
  $('ul#error_list').find('li').text(' ');
}

$(document).ready(function () {
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
