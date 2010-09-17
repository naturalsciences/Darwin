<script type="text/javascript">

$(document).ready(function () {
  $('body').widgets_screen(
  {
    collection_img_up: '<?php echo  image_path('widgets_expand_up_button.png');?>',
    collection_img_down: '<?php echo  image_path('widget_expand_button.png');?>'
  });


  $('ul.column_menu > li > ul > li').each(function(){
    hide_or_show($(this));
  });
  initIndividualColspan() ;
  $("ul.column_menu > li > ul > li").click(function(){
    update_list($(this));
    hide_or_show($(this));
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
                <?php foreach($columns['specimen'] as $col_name => $col):?>
                  <li class="<?php echo $field_to_show[$col_name]; ?>" id="li_<?php echo $col_name;?>">
                    <span class="<?php echo($field_to_show[$col_name]=='uncheck'?'hidden':''); ?>">&#10003;</span><span class="<?php echo($field_to_show[$col_name]=='uncheck'?'':'hidden'); ?>">&#10007;</span>
                    &nbsp;<?php echo $col[1];?>
                  </li>
                <?php endforeach;?>
              </ul>
            </li>
          <?php if($source != 'specimen'):?>
             <li>
              <div class="cols_title"><?php echo __('Individuals');?></div>
              <ul id="specimen_cols">
                <?php foreach($columns['individual'] as $col_name => $col):?>
                  <li class="<?php echo $field_to_show[$col_name]; ?>" id="li_<?php echo $col_name;?>">
                    <span class="<?php echo($field_to_show[$col_name]=='uncheck'?'hidden':''); ?>">&#10003;</span><span class="<?php echo($field_to_show[$col_name]=='uncheck'?'':'hidden'); ?>">&#10007;</span>
                    &nbsp;<?php echo $col[1];?>
                  </li>
                <?php endforeach;?>
              </ul>
            </li>
          <?php endif;?>
          </ul>
          <div class="clear"></div>
        </div>  
        <div class="widget_collection_top">
        </div>
        <div class="widget_collection_button"><a href="#"><?php echo image_tag('widget_expand_button.png','alt=Expand');?></a></div>
</div>

