<?php slot('widget_title',__('My Saved Specimens'));  ?>
<ul class="saved_search_widget">
<?php foreach($specimens as $specimen):?>
    <li>
        <?php if($specimen->getFavorite()):?>
            <?php echo image_tag('favorite_on.png', 'alt=Favorite class=fav_img');?>
        <?php else:?>
            <?php echo image_tag('favorite_off.png', 'alt=Favorite class=fav_img');?>
        <?php endif;?>
        <?php echo link_to($specimen->getName(),'specimensearch/search?search_id='.$specimen->getId(),array('title'=>__('Go to your search')) ); ?>
    </li>
<?php endforeach;?>
</ul>
<br />
<div class="actions">
    <div class="action_button"><?php echo __("More");?></div>
    <div style="clear:right"></div>
</div>