<table class="catalogue_table_view">
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
      <?php echo $comment->getNotionConcerned();?>
    </td>
    <td>
      <?php echo $comment->getComment();?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
