<?php slot('widget_title',__('Comments'));  ?>
<table>
  <?php foreach($comments as $comment):?>
  <tr>
    <th><?php echo $comment->getNotionConcerned();?></th>
    <td>
      <a class="link_catalogue" title="<?php echo __('Edit Comment');?>" href="<?php echo url_for('comment/comment?table='.$table.'&cid='.$comment->getId().'&id='.$eid); ?>"><?php echo $comment->getFirstChars();?></a>
    </td>
  </tr>
  <?php endforeach;?>
</table>

<br />
<?php echo image_tag('add_green.png');?><a title="<?php echo __('Add Comment');?>" class="link_catalogue" href="<?php echo url_for('comment/comment?table='.$table.'&id='.$eid); ?>"><?php echo __('Add');?></a>