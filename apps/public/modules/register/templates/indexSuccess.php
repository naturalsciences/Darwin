<?php slot('title', __('Register'));  ?>  
<div class="page">
<h1><?php echo __("Register"); ?></h1>
<?php echo form_tag('register/index');?>
  <div>
    <?php echo $form->render();?>
  </div>
  <div class="check_right">
    <input type="submit" name="submit" id="submit" value="<?php echo __('Submit'); ?>">
  </div>
</div>
</form>
