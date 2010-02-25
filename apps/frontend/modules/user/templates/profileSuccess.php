<?php slot('title', __('My Profile'));  ?>        
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'users', 'eid' => $sf_user->getAttribute('db_user_id'))); ?>
<div class="page">
  <h1 class="edit_mode">Edit My Profile</h1>
    <?php use_stylesheets_for_form($form) ?>
    <?php use_javascripts_for_form($form) ?>
    <form class="edition" action="<?php echo url_for('user/profile') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
      <table>
	<tbody>
	  <?php echo $form->renderGlobalErrors() ?>
	  <tr>
	    <th><?php echo __('Login');?></th>
	    <td>
	      <?php echo $login->getUserName();?>
	    </td>
	  <tr>
	    <th><?php echo $form['title']->renderLabel() ?></th>
	    <td>
	      <?php echo $form['title']->renderError() ?>
	      <?php echo $form['title'] ?>
	    </td>
	  </tr>
	  <tr>
	    <th><?php echo $form['given_name']->renderLabel() ?></th>
	    <td>
	      <?php echo $form['given_name']->renderError() ?>
	      <?php echo $form['given_name'] ?>
	    </td>
	  </tr>
	  <tr>
	    <th><?php echo $form['family_name']->renderLabel() ?></th>
	    <td>
	      <?php echo $form['family_name']->renderError() ?>
	      <?php echo $form['family_name'] ?>
	    </td>
	  </tr>
	  <tr>
	    <th><?php echo $form['additional_names']->renderLabel() ?></th>
	    <td>
	      <?php echo $form['additional_names']->renderError() ?>
	      <?php echo $form['additional_names'] ?>
	    </td>
	  </tr>
	  <tr>
	    <th><?php echo $form['gender']->renderLabel() ?></th>
	    <td>
	      <?php echo $form['gender']->renderError() ?>
	      <?php echo $form['gender'] ?>
	    </td>
	  </tr>
	  <tr>
	    <th><?php echo $form['birth_date']->renderLabel() ?></th>
	    <td>
	      <?php echo $form['birth_date']->renderError() ?>
	      <?php echo $form['birth_date'] ?>
	    </td>
	  </tr>
	  <tr>
	    <td colspan="2"><hr /></td>
	  </tr>
	  <tr>
	    <th><?php echo $form['password']->renderLabel() ?></th>
	    <td>
	      <?php echo $form['password']->renderError() ?>
	      <?php echo $form['password'] ?>
	    </td>
	  </tr>
	  <tr>
	    <th><?php echo $form['password_again']->renderLabel() ?></th>
	    <td>
	      <?php echo $form['password_again']->renderError() ?>
	      <?php echo $form['password_again'] ?>
	    </td>
	  </tr>
	  <tr class="trusted_user_links">
	    <td colspan="2">
		<a href="#" class="display_value">> <?php echo __('Show link with people');?> <</a>
		<a href="#" class="hide_value hidden">< <?php echo __('Hide link with people');?>  ></a>
	    </td>
	  </tr>
	  <tr class="trusted_user hidden">
	    <th><?php echo $form['people_id']->renderLabel('Reference to a People') ?></th>
	    <td class="trust_level_<?php echo $form->getObject()->getApprovalLevel()?>">
	      <?php echo $form['people_id']->renderError() ?>
	      <?php if($form->getObject()->getApprovalLevel() == 0):?>
		<h3 class="status"><?php echo __('No link saved');?></h3>
	      <?php elseif($form->getObject()->getApprovalLevel() == 1):?>
		<h3 class="status"><?php echo __('The link with your "People" is waiting for approval');?></h3>
	      <?php elseif($form->getObject()->getApprovalLevel() == 2):?>
		<h3 class="status"><?php echo __('The link with your "People" is approved');?></h3>
	      <?php endif;?>
	      <?php echo $form['people_id'] ?>
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
$('.display_value').click(function(){
  $('.trusted_user').show();
  $(this).hide();
  $('.hide_value').show();
});

$('.hide_value').click(function(){
  $('.trusted_user').hide();
  $(this).hide();
  $('.display_value').show();
});
</script>

 <?php include_partial('widgets/screen', array(
	'widgets' => $widgets,
	'category' => 'userswidget',
	'columns' => 1,
	'options' => array('eid' => $form->getObject()->getId(), 'table' => 'users')
	)); ?>
</div>