<?php slot('title', __('Edit Profile'));  ?>        
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'users','eid'=> (! $form->getObject()->isNew() ? $form->getObject()->getId() : null ))); ?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>                                                                                              
<div class="page">
  <h1 class="edit_mode"><?php echo __(sprintf("Profile for %s", $form->getObject()->getFormatedName() )) ; ?></h1>
  <form class="edition" action="<?php echo url_for('user/edit') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <input type="hidden" name="id" value="<?php echo $form->getObject()->getId() ?>">
  <table>
  <tbody>
  <tr>
    	 <th><?php echo $form['db_user_type']->renderLabel() ?></th>
	 <td>
	      <?php echo $form['db_user_type']->renderError() ?>
	      <?php echo $form['db_user_type'] ?>
	 </td>
  </tr>
  <?php include_partial('profile', array('form' => $form)) ?>
  <tr>
    <td colspan="2"><hr /></td>
  </tr>
  <tr class="trusted_user_links">
    <td colspan="2">
	<a href="#" class="display_value"> &gt; <?php echo __('Show link with '.(isset($form['title'])?'people':'institution'));?> &lt;</a>
	<a href="#" class="hide_value hidden"> &lt; <?php echo __('Hide link with '.(isset($form['title'])?'people':'institution'));?> &gt;</a>
    </td>
  </tr>
  <tr class="trusted_user hidden">
    <th><?php echo $form['people_id']->renderLabel('Reference to a '.isset($form['title'])?'People':'Institution') ?></th>
    <td class="trust_level_2">
      <?php echo $form['people_id']->renderError() ?>
      <?php echo $form['people_id'] ?>
    </td>
  </tr>
  <tr class="trusted_user_links">
    <td colspan="2">
	<a href="<?php echo url_for('user/widget?id='.$form->getObject()->getId()) ?>"><?php echo __('Edit user widgets');?></a>
    </td>
  </tr>
</tbody>
<tfoot>
  <tr>
    <td colspan="2">
      <?php echo $form->renderHiddenFields(false) ?>
      <a href="<?php echo url_for('@homepage') ?>"><?php echo __('Cancel');?></a>
      <input id="submit" type="submit" value="<?php echo __('Save');?>" />
    </td>
  </tr>
</tfoot>
 </table>
</form>

<script type="text/javascript">
$(document).ready(function () {
  $('.display_value').click(function(event){
    event.preventDefault();
    $('.trusted_user').show();
    $(this).hide();
    $('.hide_value').show();
  });

  $('.hide_value').click(function(event){
    event.preventDefault();
    $('.trusted_user').hide();
    $(this).hide();
    $('.display_value').show();
  });

  $('body').catalogue({});
});

</script>
  <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'userswidget',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'users')
	)); ?>
</div>
