<?php slot('widget_title',__('My Saved Searches'));  ?>
<ul class="saved_search_widget">
<?php foreach($searches as $search):?>
    <li>
        <div class="w_search_label">
            <?php echo image_tag('rounded_blue_square.png', 'alt=-');?><?php echo __('Specimens');?>
        </div>
        <?php if($search->getFavorite()):?>
            <?php echo image_tag('favorite_on.png', 'alt=Favorite class=fav_img');?>
        <?php else:?>
            <?php echo image_tag('favorite_off.png', 'alt=Favorite class=fav_img');?>
        <?php endif;?>
        <span><?php echo $search->getName();?></span>
    </li>
<?php endforeach;?>
</ul>
<br />
<div class="actions">
    <div class="action_button"><?php echo __("More");?></div>
    <div style="clear:right"></div>
</div>
