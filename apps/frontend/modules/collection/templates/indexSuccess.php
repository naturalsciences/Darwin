<script language="javascript">
$(document).ready(function () {
    $('.treelist li:not( li:has(ul) ) img.tree_cmd').hide();
    $('.collapsed').click(function()
    {
        $(this).hide();
        $(this).siblings('.expanded').show();
        $(this).parent().siblings('ul').show();
    });
    
    $('.expanded').click(function()
    {
        $(this).hide();
        $(this).siblings('.collapsed').show();
        $(this).parent().siblings('ul').hide();
    });
});
</script>
<div class="page">
    <h1>Collection List</h1>
    <?php foreach($institutions as $institution):?>
        <h2><?php echo $institution->getFormatedName();?></h2>
        <div class="treelist" id="collection_tree">
            <?php $prev_level = 0;?>
            <?php foreach($institution->Collections as $col_item):?>
                <?php if($prev_level < $col_item->getLevel()):?>
                    <ul>
                <?php else:?>
                    </li>
                    <?php if($prev_level > $col_item->getLevel()):?>
                        <?php echo str_repeat('</ul></li>',$prev_level-$col_item->getLevel());?>
                    <?php endif;?>
                <?php endif;?>

                <li><div class="col_name">
                <?php echo image_tag ('individual_expand.png', array('alt' => '+', 'class'=> 'tree_cmd collapsed'));?>
                <?php echo image_tag ('individual_expand_up.png', array('alt' => '-', 'class'=> 'tree_cmd expanded'));?>
                <span><?php echo $col_item->getName();?> <?php echo link_to('(e)','collection/edit?id='.$col_item->getId());?></span></div>

                <?php $prev_level =$col_item->getLevel();?>
            <?php endforeach;?>
            <?php echo str_repeat('</li></ul>',$col_item->getLevel());?>

        </div>
    <?php endforeach;?>
    <br /><br />
    <p>
        <?php echo image_tag('add_green.png');?><a href="<?php echo url_for('collection/new') ?>">New</a>
    </p>
</div>