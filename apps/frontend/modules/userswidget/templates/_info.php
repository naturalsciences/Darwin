<table class="catalogue_table">
  <thead>
    <tr>
      <th><?php echo __('Login Type');?></th>
      <th><?php echo __('Login System Id');?></th>
      <th><?php echo __('User Name');?></th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($login_info as $login_info):?>
  <tr>
    <td>
    <a class="link_catalogue" title="<?php echo __('Edit Login');?>"  href="<?php echo url_for('user/loginInfo?id='.$login_info->getId().'&user_ref='.$login_info->getUserRef());?>"">
      <?php echo $login_info->getLoginType();?>
    </a>
    </td>
    <td>
       <?php echo($login_info->getLoginSystem()?$login_info->getLoginSystem():"-");?>
    </td>
    <td>
       <?php echo($login_info->getUserName());?>
    </td>
    <td class="widget_row_delete">
    <?php if(Doctrine::getTable('Users')->findUser($sf_user->getAttribute('db_user_id'))->getDbUserType() > 2) : ?>
      <a id='edit_info' class="widget_row_delete" href="<?php echo url_for('catalogue/deleteRelated?table=users_login_infos&id='.$login_info->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?>
      </a>
    <?php endif ; ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<br />
<?php if($sf_user->getAttribute('db_user_type') > 2) : ?>
<?php echo image_tag('add_green.png');?>
<a id='add_info' title="<?php echo __('Add Login');?>" class="link_catalogue" href="<?php echo url_for('user/loginInfo?user_ref='.$eid);?>"> 
<?php echo __('Add');?>
</a>
<?php endif ; ?>

