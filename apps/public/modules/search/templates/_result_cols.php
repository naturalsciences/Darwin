<script type="text/javascript">

$(document).ready(function () {
  $('#fld_spec legend').click(function()
  {
    if( $(this).parent().attr('class') == 'closed')
    {
      $(this).parent().attr('class','opened') ;
      $(this).parent().find('table').show();
      $(this).find('.collapsed').show();
      $(this).find('.expanded').hide();
    }
    else
    {
      $(this).parent().attr('class','closed') ;
      $(this).parent().find('table').hide();
      $(this).find('.collapsed').hide();
      $(this).find('.expanded').show();
    }
  });
  $('#fld_spec').attr('class','closed') ;
  $('#fld_spec').find('table').hide();
  $('#fld_spec').find('.collapsed').hide();
  $('#fld_spec').find('.expanded').show();    
});
</script>
<div id="fields">
<fieldset id="fld_spec" class="opened">
  <legend>
    <?php echo image_tag("blue_expand.png", array('alt' => '+', 'class'=> 'tree_cmd expanded')); ?>
    <?php echo image_tag ("blue_expand_up.png", array('alt' => '-', 'class'=> 'tree_cmd collapsed')) ; ?>
    <?php echo __('Specimen criteria');?></legend> 
  <table>
    <tr><td>
      <ul class="column_menu">
        <li>
          <div class="cols_title"><?php echo __('Specimen');?></div>
          <ul id="specimen_cols">
            <?php foreach($columns['specimen'] as $col_name => $col):?>
              <li class="<?php echo $field_to_show[$col_name]; ?>" id="li_<?php echo $col_name;?>">
                <span class="<?php echo($field_to_show[$col_name]=='uncheck'?'hidden':''); ?>">&#10003;</span><span class="<?php echo($field_to_show[$col_name]=='uncheck'?'':'hidden'); ?>">&#10007;</span>
                &nbsp;<?php echo $col[1];?>
              </li>
            <?php endforeach;?>
          </ul>
        </li>
     </ul>  
    </td><td>
      <ul class="column_menu">
        <li>
          <div class="cols_title"><?php echo __('Individual');?></div>
          <ul id="specimen_cols">
            <?php foreach($columns['individual'] as $col_name => $col):?>
              <li class="<?php echo $field_to_show[$col_name]; ?>" id="li_<?php echo $col_name;?>">
                <span class="<?php echo($field_to_show[$col_name]=='uncheck'?'hidden':''); ?>">&#10003;</span><span class="<?php echo($field_to_show[$col_name]=='uncheck'?'':'hidden'); ?>">&#10007;</span>
                &nbsp;<?php echo $col[1];?>
              </li>
            <?php endforeach;?>
          </ul>
        </li>    
      </ul>
     </td>
    </tr>
  </table>
</fieldset>
</div>

