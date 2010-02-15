<?php slot('title', __('My Profile'));  ?>        
<?php include_partial('widgets/list', array('widgets' => $widgets, 'category' => 'users', 'eid' => $sf_user->getAttribute('db_user_id'))); ?>
<div class="page">
  <h1 class="edit_mode">Edit My Profile</h1>


    <?php use_stylesheets_for_form($form) ?>
    <?php use_javascripts_for_form($form) ?>
    <form class="edition" action="<?php echo url_for('account/profile') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
      <table>
	<tbody>
	  <?php echo $form->renderGlobalErrors() ?>
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

    <ul class="board_col one_col encod_screen">
      <?php foreach($widgets as $id => $widget):?>
	<?php if(!$widget->getVisible()) continue;?>
	<?php include_partial('widgets/wlayout', array(
	    'widget' => $widget->getGroupName(),
	    'is_opened' => $widget->getOpened(),
	    'category' => 'userswidget',
	    'options' => array('eid' => $form->getObject()->getId(), 'table' => 'users')
	    )); ?>
      <?php endforeach;?>
    </ul>

</div>