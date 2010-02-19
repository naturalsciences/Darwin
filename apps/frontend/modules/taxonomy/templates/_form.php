<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form class="edition" action="<?php echo url_for('taxonomy/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['name']->renderLabel() ?></th>
        <td>
          <?php echo $form['name']->renderError() ?>
          <?php echo $form['name'] ?>

	  <ul class="name_tags">
	    <li alt="name">Name Part</li>
	    <li alt="year">Year</li>
	  </ul>

        </td>
	<td rowspan="5">
	  <div id="catalogue_keywords">
	       <table>
		<tbody>	
		  <?php if(isset($keywords)):?>
		    <?php foreach($keywords as $keyword):?>
		      <?php include_partial('catalogue/nameValue', array('form' => new ClassificationKeywordsForm($keyword)));?>
		    <?php endforeach;?>
		  <?php endif;?>
		</tbody>
	       </table>
	  </div>
	  

	  <script language="javascript">
	    $(document).ready(function () {

	  $('.name_tags li').click(function()
	  {

  $.ajax(
  {
    type: "GET",
    url: "<?php echo url_for('catalogue/addValue');?>/num/" + (0+$('#catalogue_keywords tbody tr').length) + "/keyword/" + $(this).attr('alt') + "/value/" + returnText($('#taxonomy_name')),
    success: function(html)
    {
      $('#catalogue_keywords tbody').append(html);
    }
  });

	  });

      $('.clear_prop').live('click', function()
      {

	parent = $(this).closest('tr');
	if(parent.hasClass('new_record'))
	{
	  parent.remove();
	}
	else
	{
	  $(parent).find('input').val('');
	  $(parent).hide();
	}

      });

	});
	  </script>
	</td>
      </tr>
      <tr>
        <th><?php echo $form['level_ref']->renderLabel() ?></th>
        <td>
          <?php echo $form['level_ref']->renderError() ?>
          <?php echo $form['level_ref'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['status']->renderLabel() ?></th>
        <td>
          <?php echo $form['status']->renderError() ?>
          <?php echo $form['status'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['extinct']->renderLabel() ?></th>
        <td>
          <?php echo $form['extinct']->renderError() ?>
          <?php echo $form['extinct'] ?>
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
    <tfoot>
      <tr>
        <td colspan="2">
          <?php if (!$form->getObject()->isNew()): ?>
            <?php echo link_to(__('New Taxa'), 'taxonomy/new') ?>
          <?php endif?>

          <?php echo $form['id']->render() ?>  &nbsp;<a href="<?php echo url_for('taxonomy/index') ?>"><?php echo __('Cancel');?></a>

          <?php if (!$form->getObject()->isNew()): ?>
            <?php echo link_to(__('Delete'), 'taxonomy/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Are you sure?'))) ?>
          <?php endif; ?>

          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
	<td></td>
      </tr>
    </tfoot>
  </table>
</form>
