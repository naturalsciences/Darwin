<?php slot('widget_title',__('My Saved Searches'));  ?>
<ul class="saved_search_widget">
<?php foreach($searches as $search):?>
    <li>
        <div class="w_search_label widget_row_delete">
          <a id="edit_info" class="widget_row_delete" href="<?php echo url_for('savesearch/deleteSavedSearch?table=my_saved_searches&id='.$search->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?></a>
        </div>
        <?php if($search->getFavorite()):?>
            <?php echo image_tag('favorite_on.png', 'alt=Favorite class=fav_img');?>
        <?php else:?>
            <?php echo image_tag('favorite_off.png', 'alt=Favorite class=fav_img');?>
        <?php endif;?>
        <span><?php echo link_to($search->getName(),'specimensearch/search?search_id='.$search->getId(),array('title'=>__('Go to your search')) ); ?></span>
        <?php echo link_to(image_tag('edit.png'),'specimensearch/index?search_id='.$search->getId());?>
    </li>
<?php endforeach;?>
</ul>
<br />
<div class="actions">
    <div class="action_button"><?php echo __("More");?></div>
    <div style="clear:right"></div>
</div>
