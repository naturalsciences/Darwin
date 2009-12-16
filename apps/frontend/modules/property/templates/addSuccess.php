<div id="tests">

<?php if (isset($message)): ?>
  <div class="flash_save"><?php echo $message ?></div>
<?php endif; ?>

<form action="<?php echo url_for('property/add' .  ($form->getObject()->isNew() ? '': '?rid='.$form->getObject()->getId() ) );?>" method="post" id="property_form">
<?php echo $form['referenced_relation'];?>
<?php echo $form['record_id'];?>
<table>
  <tr>
      <td colspan="2">
        <?php echo $form->renderGlobalErrors() ?>
      </td>
  </tr>
  <tr>
    <th><?php echo $form['property_type']->renderLabel();?></th>
    <td>
      <?php echo $form['property_type']->renderError(); ?>
      <?php echo $form['property_type'];?>
  </td>
  </tr>
  <tr>
    <th><?php echo $form['property_sub_type']->renderLabel();?></th>
    <td>
      <?php echo $form['property_sub_type']->renderError(); ?>
      <?php echo $form['property_sub_type'];?>
    </td>
  </tr>
  <tr>
    <th><?php echo $form['property_qualifier']->renderLabel();?></th>
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


</table>


<table class="encoding proprety_values">
  <thead>
    <tr>
      <th>Value</th>
      <th>Accuracy</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <tr class="unit">
    <td><label>Unit : </label><?php echo $form['property_unit'];?></td>
    <td><label>Unit : </label><?php echo $form['property_accuracy_unit'];?></td>
    <td></td>
  </tr>
  <?php foreach($form['PropertiesValues'] as $form_value):?>
    <tr>
      <?php include_partial('prop_value', array('form' => $form_value));?>
    </tr>
  <?php endforeach;?>
  </tbody>
</table>

 <div class='add_value'>
  <a href="<?php echo url_for('property/addValue'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>/num/" id="add_prop_value">Add Value</a>
  </div>

 <?php if(! $form->getObject()->isNew()):?><button id="delete"><?php echo __('Delete');?></button><?php endif;?> <input type="submit" />

</form>

<script  type="text/javascript">
  $(document).ready(function () {
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

      $("#delete").click(function()
      {
	$.ajax({
	  url: '<?php echo url_for('property/delete?id='.$form->getObject()->getId())?>',
	  success: function(html){
	    if(html == "ok" )
	    {
	      $('.qtip-button').click();
	    }
	    else
	    {
	      addError(html);
	    }
	  },
	});
	return false;
      });

    $('.clear_prop').live('click',function (){
      parent = $(this).closest('tr');
      nvalue='';
      $(parent).find('input').val(nvalue);
      $(parent).hide();
    });

    $('form#property_form').submit(function () {
      $('form#property_form input[type=submit]').attr('disabled','disabled');
      $.ajax({
	  type: "POST",
	  url: $(this).attr('action'),
	  data: $(this).serialize(),
	  success: function(html){
	    $('form#property_form').parent().before(html).remove();
	  }
      });
      return false;
    });

    $('#add_prop_value').click(function () {
	$.ajax({
	  type: "GET",
	  url: $(this).attr('href')+ (0+$('.proprety_values tbody tr').length),
	  success: function(html){
	    $('.proprety_values tbody').append(html);
	  }
	});
	return false;
    });
  });
</script>
</div>