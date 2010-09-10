<option value=""></option>
<?php foreach($actions[$source] as $action => $name):?>
  <option value="<?php echo $action;?>"><?php echo __($name);?></option>
<?php endforeach;?>