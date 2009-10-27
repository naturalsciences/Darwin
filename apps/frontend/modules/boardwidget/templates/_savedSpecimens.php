<?php slot('widget_title',__('My Saved Specimens'));  ?>
<ul class="saved_search_widget">
<?php foreach($specimens as $specimens):?>
    <li>
        <?php if($specimens->getFavorite()):?>
            <?php echo image_tag('favorite_on.png', 'alt=Favorite class=fav_img');?>
        <?php else:?>
            <?php echo image_tag('favorite_off.png', 'alt=Favorite class=fav_img');?>
        <?php endif;?>
        <span><?php echo $specimens->getName();?></span>
    </li>
<?php endforeach;?>
</ul>
<br />
<div class="actions">
    <div class="action_button"><?php echo __("More");?></div>
    <div style="clear:right"></div>
</div>