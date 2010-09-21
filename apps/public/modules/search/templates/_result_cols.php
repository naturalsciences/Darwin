<script type="text/javascript">

$(document).ready(function () {
  $('body').widgets_screen(
  {
    collection_img_up: '<?php echo  image_path('widgets_expand_up_button.png');?>',
    collection_img_down: '<?php echo  image_path('widget_expand_button.png');?>'
  });
  
});
</script>

<?php use_stylesheet('widgets.css'); ?>
<?php use_javascript('widgets.js'); ?>
<div class="widget_collection_global search_columns">
        <div class="widget_collection_container">
           <ul class="column_menu">
             <li>
              <div class="cols_title"><?php echo __('Specimen');?></div>
              <ul id="specimen_cols">
                <?php foreach($columns->getRaw('specimen') as $col_name => $col):?>
                  <li class="<?php echo $field_to_show->getRaw($col_name); ?>" id="li_<?php echo $col_name;?>">
                    <span class="<?php echo($field_to_show->getRaw($col_name)=='uncheck'?'hidden':''); ?>">&#10003;</span><span class="<?php echo($field_to_show->getRaw($col_name)=='uncheck'?'':'hidden'); ?>">&#10007;</span>
                    &nbsp;<?php echo $col[1];?>
                  </li>
                <?php endforeach;?>
              </ul>
            </li>
            <li>
              <div class="cols_title"><?php echo __('Individual');?></div>
              <ul id="specimen_cols">
                <?php foreach($columns->getRaw('individual') as $col_name => $col):?>
                  <li class="<?php echo $field_to_show->getRaw($col_name); ?>" id="li_<?php echo $col_name;?>">
                    <span class="<?php echo($field_to_show->getRaw($col_name)=='uncheck'?'hidden':''); ?>">&#10003;</span><span class="<?php echo($field_to_show->getRaw($col_name)=='uncheck'?'':'hidden'); ?>">&#10007;</span>
                    &nbsp;<?php echo $col[1];?>
                  </li>
                <?php endforeach;?>
              </ul>
            </li>
          </ul>
          <div class="clear"></div>
        </div>  
        <div class="widget_collection_top">
        </div>
        <div class="widget_collection_button"><a href="#"><?php echo image_tag('widget_expand_button.png','alt=Expand');?></a></div>
</div>

