<option value=""></option>
<?php foreach($options as $option):?>
  <option value="<?php echo $option->$opt_method();?>"><?php echo $option->$opt_method();?></option>
<?php endforeach;?>