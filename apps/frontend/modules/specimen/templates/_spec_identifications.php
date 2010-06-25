<tbody class="spec_ident_data" id="spec_ident_data_<?php echo $row_num;?>">
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
      <?php echo image_tag('remove.png', 'alt=Delete class=clear_identification id=clear_identification_'.$row_num); ?>
      <?php echo $form->renderHiddenFields();?>
    </td>    
  </tr>
  <tr class="spec_ident_identifiers">
    <td></td>
    <td colspan="4">
      <?php $borderClass = (!$form['Identifiers']->count() && !$form['newIdentifier']->count())?'':'green_border';?>
      <table class="property_values identifiers <?php echo $borderClass;?>" id="spec_ident_identifiers_<?php echo $row_num;?>">
        <thead style="<?php echo ($form['Identifiers']->count() || $form['newIdentifier']->count())?'':'display: none;';?>" class="spec_ident_identifiers_head">
          <tr>
            <td colspan="3"><?php echo __('Identifiers');?></td>
          </tr>
        </thead>
	<?php $retainedKey = 0;?>
        <?php foreach($form['Identifiers'] as $form_value):?>
          <?php include_partial('specimen/spec_identification_identifiers', array('form' => $form_value, 'rownum'=>$retainedKey, 'identnum' => $row_num));?>
	  <?php $retainedKey = $retainedKey+1;?>
        <?php endforeach;?>
        <?php foreach($form['newIdentifier'] as $form_value):?>
          <?php include_partial('specimen/spec_identification_identifiers', array('form' => $form_value, 'rownum'=>$retainedKey, 'identnum' => $row_num));?>
	  <?php $retainedKey = $retainedKey+1;?>
        <?php endforeach;?>
        <tfoot>
          <tr>
            <td colspan="3">
              <div class="add_code">
                <a href="<?php echo url_for($module.'/addIdentifier'.(($spec_id == 0) ? '': '?spec_id='.$spec_id.(($individual_id == 0) ? '': '&individual_id='.$individual_id))).'/num/'.$row_num;?>/identifier_num/" class="hidden"></a>
                <a class="add_identifier_<?php echo $row_num ;?>" href="<?php echo url_for('people/choose?only_role=4');?>"><?php echo __('Add identifier');?></a>              
              </div>
            </td>
          </tr>
        </tfoot>
      </table>
    </td>
    <td></td>
  </tr>
</tbody>
<script  type="text/javascript">
  $(document).ready(function () {
    $("#clear_identification_<?php echo $row_num;?>").click( function()
    {
      parent = $(this).closest('tbody');
      nvalue='';
      $(parent).find('input[id$=\"_value_defined\"]').val(nvalue);
      $(parent).hide();
      reOrderIdent();
      visibles = $('table#identifications tbody.spec_ident_data:visible').size();
      if(!visibles)
      {
        $(this).closest('table#identifications').find('thead.spec_ident_head').hide();
      }
    });

    $("#spec_ident_identifiers_<?php echo $row_num; ?>").sortable({
      placeholder: 'ui-state-highlight',
      handle: '.spec_ident_identifiers_handle',
      axis: 'y',
      change: function(e, ui) {
                forceIdentifiersHelper(e,ui);
              },
      deactivate: function(event, ui) {
                    reOrderIdentifiers($(this).attr('id'));
                  }
    });
    
    $("a.add_identifier_<?php echo $row_num ;?>").click(function(){
      only_role = 0 ;
      $(this).qtip({
          content: {
              title: { text : 'Choose an identifier', button: 'X' },
              url: $(this).attr('href')
          },
          show: { when: 'click', ready: true },
          position: {
              target: $(document.body), // Position it via the document body...
              corner: 'center' // ...at the center of the viewport
          },
          hide: false,
          style: {
              width: { min: 876, max: 1000},
              border: {radius:3},
              title: { background: '#5BABBD', color:'white'}
          },
          api: {
              beforeShow: function()
              {
                  // Fade in the modal "blanket" using the defined show speed
                ref_element_id = null;
                ref_element_name = null;
                only_role = 4 ;
                ref_table = 'table#spec_ident_identifiers_<?php echo $row_num;?>';
                addBlackScreen()
                $('#qtip-blanket').fadeIn(this.options.show.effect.length);
              },
              beforeHide: function()
              {
                  // Fade out the modal "blanket" using the defined hide speed
                  $('#qtip-blanket').fadeOut(this.options.hide.effect.length).remove();
              },
	         onHide: function()
	         {
              $('.result_choose_identifier').die('click') ;
	            $(this.elements.target).qtip("destroy");
	         }
           }
      });
      return false;
   });
  });
</script>
