<?php slot('widget_title',__('Comments'));  ?>
<table class="catalogue_table">
  <thead>
    <tr>
      <th><?php echo __('Notion');?></th>
      <th><?php echo __('Comment');?></th>
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
      <?php echo $comment->getFirstChars();?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>

<br />
<?php echo image_tag('add_green.png');?><a title="<?php echo __('Add Comment');?>" class="link_catalogue" href="<?php echo url_for('comment/comment?table='.$table.'&id='.$eid); ?>"><?php echo __('Add');?></a>