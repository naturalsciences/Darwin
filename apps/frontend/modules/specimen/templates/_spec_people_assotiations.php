        <tbody class="spec_ident_collectors_data">
          <?php if($form->hasError()): ?>
            <tr>
              <td colspan="3">
                <?php echo $form->renderError();?>
              </td>
            </tr>
          <?php endif;?>
          <tr class="spec_ident_collectors_data">
            <td class="spec_ident_collectors_handle"><?php echo image_tag('drag.png');?></td>
            <td><?php echo $form['people_ref']->render();?></td>
            <td class="widget_row_delete">
              <?php echo image_tag('remove.png', 'alt=Delete class=clear_collectors'); ?>
              <?php echo $form->renderHiddenFields();?>
            </td>
          </tr>
        </tbody>
