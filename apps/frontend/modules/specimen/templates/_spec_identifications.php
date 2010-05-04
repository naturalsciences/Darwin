<tbody class="spec_ident_data">
  <?php if($form->hasError()): ?>
  <tr>
    <td colspan="6">
      <?php echo $form->renderError();?>
    </td>
  </tr>
  <?php endif;?>
  <tr class="spec_ident_data">
    <td class="spec_ident_handle">
      <?php echo image_tag('drag.png');?>
    </td>
    <td>
      <?php echo $form['notion_date'];?>
    </td>
    <td>
      <?php echo $form['notion_concerned'];?>
    </td>
    <td>
      <?php echo $form['value_defined'];?>
    </td>
    <td>
      <?php echo $form['determination_status'];?>
    </td>
    <td class="widget_row_delete">
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_identification'); ?>
      <?php echo $form->renderHiddenFields();?>
    </td>    
  </tr>
  <tr class="spec_ident_identifiers">
    <td colspan="2"></td>
    <td colspan="3">
      <table class="property_values">
        <thead>
          <tr>
            <td colspan="3"><?php echo __('Identifiers');?></td>
          </tr>
        </thead>
        <tbody class="spec_ident_identifiers_data">
          <tr>
            <td class="spec_ident_identifiers_handle"><?php echo image_tag('drag.png');?></td>
            <td>Choose</td>
            <td class="widget_row_delete">
              <?php echo image_tag('remove.png', 'alt=Delete class=clear_identification'); ?>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3">
              <div class="add_code">
                <a href="<?php echo url_for('specimen/addIdentifier');?>" id="add_identifier"><?php echo __('Add Ident.');?></a>
              </div>
            </td>
          </tr>
        </tfoot>
      </table>
    </td>
    <td></td>
  </tr>
</tbody>