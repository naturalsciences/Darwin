<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form class="edition" action="<?php echo url_for('user/'.($form->getObject()->isNew() ? 'create' : 'edit').(!$form->getObject()->isNew() ? '?id='.$user->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

  <table>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <?php if ($mode != 'profile') : ?>
        <tr>
          <th><?php echo $form['db_user_type']->renderLabel() ?></th>
	        <td>
	          <?php echo $form['db_user_type']->renderError() ?>
	          <?php echo $form['db_user_type'] ?>
	        </td>
        </tr>
      <?php endif ?>
      <?php if ($mode == 'new') : ?>
        <tr>
          <th><?php echo $form['is_physical']->renderLabel() ?></th>
          <td>
            <?php echo $form['is_physical']->renderError() ?>
            <?php echo $form['is_physical'] ?>
          </td>
        </tr>     
        <tr id="is_not_physical">
          <th><?php echo $form['sub_type']->renderLabel() ?></th>
          <td>
            <?php echo $form['sub_type']->renderError() ?>
            <?php echo $form['sub_type'] ?>
          </td>
        </tr>
        <tr id="is_physical">
          <th><?php echo $form['title']->renderLabel() ?></th>
          <td>
            <?php echo $form['title']->renderError() ?>
            <?php echo $form['title'] ?>
          </td>
        </tr>
        <tr id="is_physical">
          <th><?php echo $form['gender']->renderLabel() ?></th>
          <td>
            <?php echo $form['gender']->renderError() ?>
            <?php echo $form['gender'] ?>
          </td>
        </tr>  
      <tr>
        <th><?php echo $form['given_name']->renderLabel() ?></th>              
      <?php elseif($user->getIsPhysical()) : ?>
        <tr>
          <th><?php echo $form['title']->renderLabel() ?></th>
          <td>
            <?php echo $form['title']->renderError() ?>
            <?php echo $form['title'] ?>
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
        <th><?php echo $form['given_name']->renderLabel('Given Name') ?></th>              
      <?php else : ?>
         <tr>
          <th><?php echo $form['sub_type']->renderLabel() ?></th>
          <td>
            <?php echo $form['sub_type']->renderError() ?>
            <?php echo $form['sub_type'] ?>
          </td>
        </tr>   
      <tr>
        <th><?php echo $form['given_name']->renderLabel('Abbreviation') ?></th>          
      <?php endif ; ?>
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
      <?php if(!$form->getObject()->isNew()) : ?>
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
            <?php if($mode == 'edit') : ?>
  	          <a href="<?php echo url_for('user/widget?id='.$user->getId()) ?>"><?php echo __('Edit user widgets');?></a>
  	        <?php else : ?>
  	          <a href="<?php echo url_for('user/widget') ?>"><?php echo __('Edit your widgets');?></a>
  	        <?php endif ; ?>
          </td>
        </tr>    
      <?php endif ; ?>  
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          <?php if ($mode == 'edit'): ?>
            &nbsp;<?php echo link_to('Delete', 'user/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <a href="<?php echo url_for('@homepage') ?>"><?php echo __('Cancel');?></a>
          <input id="submit" type="submit" value="<?php echo __('Save');?>" />
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<script type="text/javascript">
$(document).ready(function () {

  $('body').catalogue({});

  $(':checkbox#users_is_physical').change(function(){
    if ($(this).attr("checked"))
    {
      $('tr#is_not_physical').hide();
      $('tr#is_physical').fadeIn();
      $('label[for="users_family_name"]').html("Family Name") ;
      $('label[for="users_given_name"]').html("Given Name") ;
    }
    else
    {
      $('tr#is_physical').hide();
      $('tr#is_not_physical').fadeIn();
      $('label[for="users_family_name"]').html("Name") ;
      $('label[for="users_given_name"]').html("Abbreviation") ;
    }
  });

  $(':checkbox#users_is_physical').change();
  
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

});
</script>
