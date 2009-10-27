<?php slot('widget_title',__('Collection'));  ?>
<?php slot('widget_mandatory_refCollection',true);  ?>
<script type="text/javascript">
$(document).ready(function () {
    $("#specimen_collection_ref_button").qtip({
        content: {
            title: { text : '<?php echo __('Choose a collection');?>', button: '<?php echo __('Close');?>' },
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

<?php echo $form['collection_ref']->renderError() ?>
<?php echo $form['collection_ref']->render() ?>