<div class="widget_collection_global">
	<div class="widget_collection_container">
		<div class="widget_preview">
		  <a href="<?php echo url_for('board/addWidget?widget=savedSearch');?>">
		    <?php echo image_tag('widged_preview_1.png');?>
		  <span class="widget_prev_title">My Search</span></a>
		</div>
		<div class="widget_preview"><a href="<?php echo url_for('board/addWidget?widget=savedSpecimens');?>"><?php echo image_tag('widged_preview_1.png');?><span class="widget_prev_title">Add Taxa</span></a></div>
		<div class="widget_preview"><a href="<?php echo url_for('board/addWidget?widget=savedSearch');?>"><?php echo image_tag('widged_preview_1.png');?><span class="widget_prev_title">Accepted Records</span></a></div>
        <br />
	</div>	
	<div class="widget_collection_top">
		<div class="help_message"><a class="close_button" href=""><?php echo image_tag('widget_help_close.png','alt=Close class=help_close');?></a>
		<?php echo __('Customise your interface !');?>
		</div>
	</div>
	<div class="widget_collection_button"><a href="#"><?php echo image_tag('widget_expand_button.png','alt=Expand');?></a></div>
</div>