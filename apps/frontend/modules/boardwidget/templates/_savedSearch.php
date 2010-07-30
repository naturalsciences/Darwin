<?php slot('widget_title',__('My Saved Searches'));  ?>
<ul class="saved_search_widget">
<?php foreach($searches as $search):?>
    <li>
        <div class="w_search_label widget_row_delete">
            <?php echo __('Specimens');?>
                    <a id='edit_info' class="widget_row_delete" href="<?php echo url_for('specimensearch/deleteSavedSearch?table=my_saved_searches&id='.$search->getId());?>" title="<?php echo __('Are you sure ?') ?>"><?php echo image_tag('remove.png'); ?></a>
        </div>
        <?php if($search->getFavorite()):?>
            <?php echo image_tag('favorite_on.png', 'alt=Favorite class=fav_img');?>
        <?php else:?>
            <?php echo image_tag('favorite_off.png', 'alt=Favorite class=fav_img');?>
        <?php endif;?>
        <span><a href="<?php echo url_for('specimensearch/index?search_id='.$search->getId()) ; ?>" title="<?php echo __('Go to your search') ; ?>"><?php echo $search->getName();?></a></span>
    </li>
<?php endforeach;?>
</ul>
<br />
<div class="actions">
    <div class="action_button"><?php echo __("More");?></div>
    <div style="clear:right"></div>
</div>
