<?php include_javascripts_for_form($form) ?>
<div id="property_screen">
<?php echo form_tag('property/add?table='.$sf_params->get('table').'&id='.$sf_params->get('id').($form->getObject()->isNew() ? '': '&rid='.$form->getObject()->getId() ), array('class'=>'edition qtiped_form', 'id'=>'property_form'));?>

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
      <th class="top_aligned"><?php echo $form['property_type']->renderLabel();?></th>
      <td>
        <?php echo $form['property_type']->renderError(); ?>
        <?php echo $form['property_type'];?>
      </td>
    </tr>
    <tr>
      <th class="top_aligned"><?php echo $form['property_sub_type']->renderLabel();?></th>
      <td>
        <?php echo $form['property_sub_type']->renderError(); ?>
        <?php echo $form['property_sub_type'];?>
      </td>
    </tr>
    <tr>
      <th class="top_aligned"><?php echo $form['property_qualifier']->renderLabel();?></th>
      <td>
        <?php echo $form['property_qualifier']->renderError(); ?>
        <?php echo $form['property_qualifier'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['date_from']->renderLabel();?></th>
      <td>
        <?php echo $form['date_from']->renderError(); ?>
        <?php echo $form['date_from'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['date_to']->renderLabel();?></th>
      <td>
        <?php echo $form['date_to']->renderError(); ?>
        <?php echo $form['date_to'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['property_method']->renderLabel();?></th>
      <td>
        <?php echo $form['property_method']->renderError(); ?>
        <?php echo $form['property_method'];?>
      </td>
    </tr>
    <tr>
      <th><?php echo $form['property_tool']->renderLabel();?></th>
      <td>
        <?php echo $form['property_tool']->renderError(); ?>
        <?php echo $form['property_tool'];?>
      </td>
    </tr>
  </tbody>
</table>
<table class="encoding property_values">
  <thead>
    <tr>
      <th><label><?php echo __("Value units") ; ?></label></th>
      <th class="unit_col"><?php echo $form['property_unit'];?></th>
      <th><label><?php echo __("Accuracy units") ; ?></label></th>
      <th class="unit_col"><?php echo $form['property_accuracy_unit'];?></th>
      <th></th>
    </tr>
  </thead>
  <tbody id="property">
    <?php foreach($form['PropertiesValues'] as $form_value):?>
      <?php include_partial('prop_value', array('form' => $form_value));?>
    <?php endforeach;?>
    <?php foreach($form['newVal'] as $form_value):?>
      <?php include_partial('prop_value', array('form' => $form_value));?>
    <?php endforeach;?>
  </tbody>
</table>
<div class='add_value'>
  <a href="<?php echo url_for('property/addValue'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/table/<?php echo $sf_request->getParameter('table');?>/num/" id="add_prop_value"><?php echo __('Add Value');?></a>
</div>
<table class="bottom_actions">
  <tfoot>
    <tr>
      <td>
        <a href="#" class="cancel_qtip"><?php echo __('Cancel');?></a>
        <?php if(! $form->getObject()->isNew()):?>
	  <a class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=catalogue_properties&id='.$form->getObject()->getId());?>" title="<?php echo __('Are you sure ?') ?>">
	    <?php echo __('Delete');?>
	  </a>
        <?php endif;?> 
        <input id="submit" type="submit" value="<?php echo __('Save');?>" />
      </td>
    </tr>
  </tfoot>
</table>
</form>

<script  type="text/javascript">
$(document).ready(function () {
  $('form.qtiped_form').modal_screen();

    $('#catalogue_properties_property_type').change(function() {
      $.get("<?php echo url_for('property/getUnit');?>/type/"+$(this).val(), function (data) {
      $("#catalogue_properties_property_unit_parent select").html(data);
      $("#catalogue_properties_property_accuracy_unit_parent select").html(data);
      $("#catalogue_properties_property_qualifier_parent select").html(' ');
      });

      $.get("<?php echo url_for('property/getSubtype');?>/type/"+$(this).val(), function (data) {
        $("#catalogue_properties_property_sub_type_parent select").html(data);
      });

    });

    $('#catalogue_properties_property_sub_type').change(function() {
      $.get("<?php echo url_for('property/getQualifier');?>/subtype/"+$(this).val(), function (data) {
        $("#catalogue_properties_property_qualifier_parent select").html(data);
      });
    });

    $('.clear_prop').live('click', clearPropertyValue);

    $('#add_prop_value').click(addPropertyValue);

  });
</script>
</div>
