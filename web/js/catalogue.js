 $(document).ready(function () {
 $("a.link_catalogue").live('click', function(){
    $(this).qtip({
        content: {
            title: { text : $(this).attr('title'), button: 'X' },
            url: $(this).attr('href')
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            corner: 'center' // ...at the center of the viewport
        },
        hide: false,
        style: {
            width: { min: 600/*, max: 1000*/}
        },
        api: {
            beforeShow: function()
            {
                addBlackScreen()
                $('#qtip-blanket').fadeIn(this.options.show.effect.length);
            },
            beforeHide: function()
            {
                // Fade out the modal "blanket" using the defined hide speed
                $('#qtip-blanket').fadeOut(this.options.hide.effect.length).remove();
            },
	    onHide: function()
	    {
		widget_parent = $(this.elements.target).closest('li.widget');
		widget_parent.find('.widget_content').load(reload_url+'/widget/'+widget_parent.attr("id"));
		//$(this.elements.target).qtip("destroy");
		hideForRefresh(widget_parent.find('.widget_content'));
	    }
        }
    });
    return false;
  });
});