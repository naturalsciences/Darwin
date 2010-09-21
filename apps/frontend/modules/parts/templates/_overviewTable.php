<?php if(count($parts)==0):?>
  <h2><?php echo __('There a currently no part. Please add one');?></h2>
<?php else:?>
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
    <th></th>    
  </tr>
  </thead>
  <tbody>
  <?php foreach($parts as $part):?>
  <tr class="rid_<?php echo $part->getId();?>">
    <td style="padding-left: <?php echo 20*($part->getLevel()-1);?>px; ">
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
    <td class="item_name"><?php echo $part->getSpecimenPart();?></td>
    <td><?php echo $part->getRoom();?></td>
    <td><?php echo $part->getRow();?></td>
    <td><?php echo $part->getShelf();?></td>
    <td><?php echo $part->getContainer();?></td>
    <td><?php echo $part->getSubContainer();?></td>
    <?php if(!isset($is_choose) || $is_choose==false):?>
      <td>
        <?php echo link_to(image_tag('edit.png'),'parts/edit?id='.$part->getId(), array('title'=>__('Edit this part')));?>
      </td>
      <td>
        <?php echo link_to(image_tag('duplicate.png',array('title'=>'Duplicate this part')), 'parts/edit?indid='.$individual->getId().'&duplicate_id='.$part->getId(),array('class' => 'duplicate_link')) ?>
      </td>      
      <td>
        <a class="row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=specimen_parts&id='.$part->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
      </td>
    <?php else:?>
      <td colspan="2">
        <div class="result_choose"><?php echo __('Choose');?></div>
      </td>
    <?php endif;?>
  </tr>
  <?php endforeach;?>
  </tbody>
  <?php if(!isset($is_choose) || $is_choose==false):?>
    <tfoot>
    <tr>
      <td colspan='10'>
        <div class="add_spec_individual">	        
          <a href="<?php echo url_for('parts/edit?indid='.$individual->getId());?>"><?php echo __('Add part');?></a>
        </div>
      </td>
    </tr>
    </tfoot>
    <?php endif;?>
</table>
<script  type="text/javascript">
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
<?php endif;?>
