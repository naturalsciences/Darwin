  <tbody  class="spec_ident_comments_data">
   <tr class="spec_ident_comments_data">
      <td>
          <?php echo $form['notion_concerned']->renderError(); ?>
          <?php echo $form['notion_concerned'];?>
      </td>
      <td>
        <?php echo $form['comment']->renderError(); ?>
        <?php echo $form['comment'];?>
      </td>
      <td class="widget_row_delete">
        <?php echo image_tag('remove.png', 'alt=Delete class=clear_comment'); ?>
        <?php echo $form->renderHiddenFields() ?>
      </td>    
    </tr>
   <tr>
  	<td colspan="3"><hr /></td>
  </tr>
  </tbody>
