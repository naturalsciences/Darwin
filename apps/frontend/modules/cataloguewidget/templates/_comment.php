<?php slot('widget_title',__('Comments'));  ?>
<table class="catalogue_table">
  <thead>
    <tr>
      <th><?php echo __('Notion');?></th>
      <th><?php echo __('Comment');?></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($comments as $comment):?>
  <tr>
    <td>
      <a class="link_catalogue" title="<?php echo __('Edit Comment');?>" 
	  href="<?php echo url_for('comment/comment?table='.$table.'&cid='.$comment->getId().'&id='.$eid); ?>">
	<?php echo $comment->getNotionConcerned();?>
      </a>
    </td>
    <td>
      <?php echo $comment->getComment();?>
    </td>
    <td class="widget_row_delete">
      <a class="widget_row_delete" href="<?php echo url_for('comment/delete?id='.$comment->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
      </a>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>

<br />
<?php if($addAllowed):?><?php echo image_tag('add_green.png');?><a title="<?php echo __('Add Comment');?>" class="link_catalogue" href="<?php echo url_for('comment/comment?table='.$table.'&id='.$eid);?>"><?php else:?><?php echo image_tag('add_grey.png');?><span class='add_not_allowed'><?php endif;?><?php echo __('Add');?><?php if($addAllowed):?></a><?php else:?></span><?php endif;?>
