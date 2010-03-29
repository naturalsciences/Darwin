<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form class="edition" action="<?php echo url_for('gtu/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th class="top_aligned"><?php echo $form['code']->renderLabel() ?></th>
        <td>
          <?php echo $form['code']->renderError() ?>
          <?php echo $form['code'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['gtu_from_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_from_date']->renderError() ?>
          <?php echo $form['gtu_from_date'] ?>
        </td>
      </tr>
      <tr>
        <th class="top_aligned"><?php echo $form['gtu_to_date']->renderLabel() ?></th>
        <td>
          <?php echo $form['gtu_to_date']->renderError() ?>
          <?php echo $form['gtu_to_date'] ?>
        </td>
      </tr>
      <tr>
        <th class="top_aligned"><?php echo $form['parent_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['parent_ref']->renderError() ?>
          <?php echo $form['parent_ref'] ?>
        </td>
      </tr>
    </tbody>
</table>

<table class="tag_groups">
  <tbody>
    <?php foreach($form['TagGroups'] as $form_value):?>
      <?php include_partial('taggroups', array('form' => $form_value));?>
    <?php endforeach;?>
    <?php foreach($form['newVal'] as $form_value):?>
      <?php include_partial('taggroups', array('form' => $form_value));?>
    <?php endforeach;?>

  </tbody>
  <tfoot>
    <tr>
	<td>
	    <select id="groups_select">
	    <option value=""></option>
	     <?php foreach(TagGroups::getGroups() as $k => $v):?>
		<option value="<?php echo $k;?>"><?php echo $v;?></option>
	      <?php endforeach;?>
	    </select>
	</td>
	<td>
	    <a href="<?php echo url_for('gtu/addGroup'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>" id="add_group"><?php echo __('Add Group');?></a>
	</td>
      </tr>
    </tfoot>
  </table>

  <table>
    <tfoot>
      <tr>
        <td>
          <?php echo $form->renderHiddenFields(true) ?>

          <?php if (!$form->getObject()->isNew()): ?>
            <?php echo link_to(__('New Gtu'), 'gtu/new') ?>
          <?php endif?>

          &nbsp;<a href="<?php echo url_for('gtu/index') ?>"><?php echo __('Cancel');?></a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'gtu/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>


<script  type="text/javascript">
$(document).ready(function () {
    $('.clear_prop').live('click', clearPropertyValue);
    $('.purposed_tags li').live('click', function()
    {
      input_el = $(this).closest('tr').find('input[id$="_tag_value"]');
      if(input_el.val().match("\;\s*$"))
	input_el.val( input_el.val() + $(this).text() );
      else
	input_el.val( input_el.val() + " ; " +$(this).text() );
      input_el.trigger('change');
    });

    $('input[id$="_tag_value"]').live('keypress',pressTags);
    $('input[id$="_tag_value"]').live('change', purposeTags);
    $('input[id$="_tag_value"]').live('click',purposeTags);

   function pressTags(event)
   {
      if (event.keyCode == 59 /* ;*/ || event.keyCode == 32 /* */ )
      {
	$(this).trigger('change');
      }
   }	
   function purposeTags()
   {
      parent_el = $(this).closest('tr');
      group_name = parent_el.find('input[name$="\[group_name\]"]').val();
      sub_group_name = parent_el.find('[name$="\[sub_group_name\]"]').val();
      if(sub_group_name=='') sub_group_name='-'
      $.ajax({
	  type: "GET",
	  url: "<?php echo url_for('gtu/purposeTag');?>" + '/group_name/' + group_name + '/sub_group_name/' + sub_group_name + '/value/'+ $(this).val(),
	  success: function(html)
	  {
	    parent_el.find('.purposed_tags').html(html);
	  }
      });
    }

    $('#add_group').click(function()
    {
      $.ajax({
	  type: "GET",
	  url: $(this).attr('href')+'/group/'+$('#groups_select').val() + '/num/' + (0+$('table.tag_groups tbody tr').length),
	  success: function(html)
	  {
	    $('table.tag_groups tbody').append(html);
	    disableUsedGroups();
	  }
	});
      return false;
    });

});
</script>
