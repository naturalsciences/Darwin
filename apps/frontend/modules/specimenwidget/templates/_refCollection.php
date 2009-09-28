<?php slot('widget_title',__('Collection'));  ?>
<?php slot('widget_mandatory_refCollection',true);  ?>
<script type="text/javascript">
$(document).ready(function () {
    $("#collection_ref").qtip({
        content: {
            title: { text : 'Choose a collection', button: 'Close' },
            url: '<?php echo url_for('collection/choose');?>'
        },
        show: { when: 'click' },
        position: {
            target: $(document.body), // Position it via the document body...
            corner: 'center' // ...at the center of the viewport
        },
        hide: false,
        style: {
            width: { min: 500, max: 1000}
        },
        api: {
            beforeShow: function()
            {
                // Fade in the modal "blanket" using the defined show speed
                addBlackScreen()
                $('#qtip-blanket').fadeIn(this.options.show.effect.length);
            },
            beforeHide: function()
            {
                // Fade out the modal "blanket" using the defined hide speed
                $('#qtip-blanket').fadeOut(this.options.hide.effect.length).remove();
            }
        }
    });
});
</script>
<div id="collection_ref_name">
    <?php if(! $form->getObject()->isNew()):?>
        <?php echo $form->getObject()->Collections->getName();?>
    <?php endif;?>
</div>
<div id="collection_ref" class="button">
    <?php echo image_tag('button_grey_left.png', 'class=left_part alt=""');?>
    <span class="but_text" style="background-image: url(/images/button_grey_center.png);">
    <?php if($form->getObject()->isNew()):?>
        Choose !
    <?php else:?>
        Change !
    <?php endif;?>
    </span>
    <?php echo image_tag('button_grey_right.png', 'class=right_part alt=""');?>
    <div style="clear: left;"> </div>
</div>
<br /><br /> <?php //@TODO: IIIIIIEEEEEK;?>

    <?php echo $form['collection_ref']->render() ?>