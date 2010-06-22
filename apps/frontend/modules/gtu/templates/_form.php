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

<?php
$tag_grouped = array();
$avail_groups = TagGroups::getGroups(); 
foreach($form['TagGroups'] as $group)
{
  $type = $group['group_name']->getValue();
  if(!isset($tag_grouped[$type]))
    $tag_grouped[$type] = array();
  $tag_grouped[$type][] = $group;
}
foreach($form['newVal'] as $group)
{
  $type = $group['group_name']->getValue();
  if(!isset($tag_grouped[$type]))
    $tag_grouped[$type] = array();
  $tag_grouped[$type][] = $group;
}
?>

<div class="tag_parts_screen" alt="<?php echo url_for('gtu/addGroup'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>">
<?php foreach($tag_grouped as  $group_key => $sub_forms):?>
  <fieldset alt="<?php echo $group_key;?>">
    <legend><?php echo __($avail_groups[$group_key]);?></legend>
    <ul>
      <?php foreach($sub_forms as $form_value):?>
	<?php include_partial('taggroups', array('form' => $form_value));?>
      <?php endforeach;?>
    </ul>
    <a class="sub_group"><?php echo __('Add Sub Group');?></a>
  </fieldset>
<?php endforeach;?>
</div>


  <div class="gtu_groups_add">
    <select id="groups_select">
      <option value=""></option>
      <?php foreach(TagGroups::getGroups() as $k => $v):?>
	<option value="<?php echo $k;?>"><?php echo $v;?></option>
      <?php endforeach;?>
    </select>
    <a href="<?php echo url_for('gtu/addGroup'. ($form->getObject()->isNew() ? '': '?id='.$form->getObject()->getId()) );?>" id="add_group"><?php echo __('Add Group');?></a>
  </div>

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
    $('#gtu_parent_ref').change(function()
    {
      $("#gtu_parent_ref_name").html(trim(ref_element_name));
    });


    $('.clear_prop').live('click', function()
    {
      parent = $(this).closest('li');
      nvalue='';
      $(parent).find('input').val(nvalue);
      $(parent).hide();

      sub_groups  = parent.parent();
      if(sub_groups.find("li:visible").length == 0)
      {
	sub_groups.closest('fieldset').hide();
	disableUsedGroups();
      }
    });

    
    function disableUsedGroups()
    {
      $('#groups_select option').removeAttr('disabled');
      $('.tag_parts_screen fieldset:visible').each(function()
      {
	var cur_group = $(this).attr('alt');
	$("#groups_select option[value='"+cur_group+"']").attr('disabled','disabled');
	if($("#groups_select option[value='"+cur_group+"']:selected"))
	  $('#groups_select').val("");
      });
    }
    disableUsedGroups();
    $('.purposed_tags li').live('click', function()
    {
      input_el = $(this).parent().closest('li').find('input[id$="_tag_value"]');
      if(input_el.val().match("\;\s*$"))
	input_el.val( input_el.val() + $(this).text() );
      else
	input_el.val( input_el.val() + " ; " +$(this).text() );
      input_el.trigger('click');
    });

    $('input[id$="_tag_value"]').live('keydown click',purposeTags);

   function purposeTags(event)
   {
      if (event.type == 'keydown')
      {
	var code = (event.keyCode ? event.keyCode : event.which);
	if (code != 59 /* ;*/ && code != $.ui.keyCode.SPACE ) return;
      }
      parent_el = $(this).closest('li');
      group_name = parent_el.find('input[name$="\[group_name\]"]').val();
      sub_group_name = parent_el.find('[name$="\[sub_group_name\]"]').val();
      if(sub_group_name == '' || $(this).val() == '') return;
      $('.purposed_tags').hide();
      $.ajax({
	  type: "GET",
	  url: "<?php echo url_for('gtu/purposeTag');?>" + '/group_name/' + group_name + '/sub_group_name/' + sub_group_name + '/value/'+ $(this).val(),
	  success: function(html)
	  {
	    parent_el.find('.purposed_tags').html(html);
	    parent_el.find('.purposed_tags').show();
	  }
      });
    }

    $('#add_group').click(function()
    {
      selected_group = $('#groups_select option:selected').val();
      selected_group_name = $('#groups_select option:selected').text();
      if(selected_group != '')
      {
	$.ajax({
	    type: "GET",
	    url: $('.tag_parts_screen').attr('alt')+'/group/'+ selected_group + '/num/' + (0+$('.tag_parts_screen ul li').length),
	    success: function(html)
	    {
	      if( $('fieldset[alt="'+selected_group+'"]').length != 0)
	      {
		fld_set = $('fieldset[alt="'+selected_group+'"]');
		fld_set.find('> ul').append(html);
		fld_set.show();
	      }
	      else
	      {
		html = '<fieldset alt="'+ selected_group +'"><legend>' + selected_group_name + '</legend><ul>'+html+'</ul><a class="sub_group"><?php echo __('Add Sub Group');?></a></fieldset>';
		$('.tag_parts_screen').append(html);
	      }
	      disableUsedGroups();
	    }
	  });
      }
      return false;
    });

    $('a.sub_group').live('click',function()
    {
      fieldset = $(this).closest('fieldset');
      selected_group = fieldset.attr('alt');
      list =  fieldset.find('> ul');
      $.ajax({
	  type: "GET",
	  url: $('.tag_parts_screen').attr('alt')+'/group/'+ selected_group + '/num/' + (0+$('.tag_parts_screen ul li').length),
	  success: function(html)
	  {
	    list.append(html);
	  }
	});
      return false;
    });

});
</script>
