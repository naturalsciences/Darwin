<div class="page">
    <h1><?php echo __('Collection List');?></h1>
    <?php include_partial('collectionTree', array('institutions' => $institutions,'is_choose' => false)) ?>
    <br /><br />
    <p>
        <?php echo image_tag('add_green.png','alt=Add');?><a href="<?php echo url_for('collection/new') ?>"><?php echo __('New');?></a>
    </p>
</div>