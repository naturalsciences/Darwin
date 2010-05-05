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
      <table class="property_values identifiers" id="spec_ident_identifiers_<?php echo $row_num;?>">
        <thead style="<?php echo ($form['Identifiers']->count() || $form['newIdentifier']->count())?'':'display: none;';?>" class="spec_ident_identifiers_head">
          <tr>
            <td colspan="3"><?php echo __('Identifiers');?></td>
          </tr>
        </thead>
        <?php foreach($form['Identifiers'] as $form_value):?>
          <?php include_partial('spec_identification_identifiers', array('form' => $form_value));?>
        <?php endforeach;?>
        <?php foreach($form['newIdentifier'] as $form_value):?>
          <?php include_partial('spec_identification_identifiers', array('form' => $form_value));?>
        <?php endforeach;?>
        <tfoot>
          <tr>
            <td colspan="3">
              <div class="add_code">
                <a href="<?php echo url_for('specimen/addIdentifier'.((!$spec_id)?'':'?spec_id='.$spec_id)).'/num/'.$row_num.((!isset($form['id']))?'':'/iid/'.$form['id']->getValue());?>/inum/" class="add_identifier"><?php echo __('Add Ident.');?></a>
              </div>
            </td>
          </tr>
        </tfoot>
      </table>
    </td>
    <td></td>
  </tr>
</tbody>