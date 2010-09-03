<?php include_javascripts_for_form($form) ?>
<div id="insurances_screen">

<?php if (isset($message)): ?>
  <div class="flash_save"><?php echo __($message); ?></div>
<?php endif; ?>
<?php echo form_tag('insurances/add?table='.$sf_params->get('table').'&id='.$sf_params->get('id').($form->getObject()->isNew() ? '': '&rid='.$form->getObject()->getId() ), array('class'=>'edition qtiped_form', 'id'=>'insurances_form'));?>

<?php echo $form['referenced_relation'];?>
<?php echo $form['record_id'];?>
<table>
  <tbody>
    <tr>
        <td colspan="2">
          <?php echo $form->renderGlobalErrors() ?>
        </td>
    </tr>
    <tr>
      <th><?php echo $form['insurance_value']->renderLabel();?></th>
      <td>
        <?php echo $form['insurance_value']->renderError(); ?>
        <?php echo $form['insurance_value'];?>
      </td>
    </tr>
    <tr>
      <th class="top_aligned"><?php echo $form['insurance_currency']->renderLabel();?></th>
      <td>
        <?php echo $form['insurance_currency']->renderError(); ?>
        <?php echo $form['insurance_currency'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['insurance_year']->renderLabel();?></th>
      <td>
        <?php echo $form['insurance_year']->renderError(); ?>
        <?php echo $form['insurance_year'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['insurer_ref']->renderLabel();?></th>
      <td>
        <?php echo $form['insurer_ref']->renderError(); ?>
        <?php echo $form['insurer_ref'];?>
      </td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="2">
        <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
        <?php if(! $form->getObject()->isNew()):?>
          <?php echo link_to(__('Delete'),'catalogue/deleteRelated?table=insurances&id='.$form->getObject()->getId(),array('class'=>'delete_button','title'=>__('Are you sure ?')));?>
        <?php endif;?> 
        <input id="submit" type="submit" value="<?php echo __('Save');?>" />
      </td>
    </tr>
  </tfoot>
</table>
</form>
<script  type="text/javascript">
   $(document).ready(function () 
   {
    $('form.qtiped_form').modal_screen();

     $('.result_choose').live('click',function () 
     {
       el = $(this).closest('tr');
       $("#insurances_insurer_ref").val(getIdInClasses(el));
       $("#insurances_insurer_ref_name").val(el.find('.item_name').text()).show();
       $('.reference_clear').show();
       $('div.search_box').slideUp();
     });
   });
</script>
  
  <div class="search_box">
    <?php include_partial('institution/searchForm', array('form' => new InstitutionsFormFilter(),'is_choose'=>true)) ?>
  </div>

</div>
