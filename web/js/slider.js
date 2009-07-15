$(window).resize(function(){
    $('.scrollContainer div.panel').css('width',$('.widget_collection_global').width()-90);
});



// when the DOM is ready...
$(document).ready(function () {
    var tab_idx=0;
    var $panels = $('#slider .scrollContainer > div');
    var $container = $('#slider .scrollContainer');
    var $scroll = $('#slider .scroll');
	
    $panels.css('width',$('.widget_collection_global').width()-90);
    $container.css('width', $panels[0].offsetWidth * $panels.length);

	
    var scrollOptions = {
        target: $scroll, // the element that has the overflow
        items: $panels,
        duration: 500,
        cycle: false,
        onBefore:function( e, elem, $pane, $items, pos ){
			/**
            * 'this' is the triggered element 
            * e is the event object
            * elem is the element we'll be scrolling to
            * $pane is the element being scrolled
            * $items is the items collection at this moment
            * pos is the position of elem in the collection
            * if it returns false, the event will be ignored
            */
            if($('#tab_'+pos).hasClass('disabled'))
            return false;
            $('.tabs li').removeClass('selected');
            $('#tab_'+pos).addClass('selected');
            tab_idx = pos;
        },
        onAfter: function(e){
            if($('#tab_'+(tab_idx+1)).hasClass('enabled'))
            {
                $('#arrow_right').attr('src','img/encod_right_enable.png');
            }
            else
            {
                $('#arrow_right').attr('src','img/encod_right_disable.png');
            }
			
            if(tab_idx !=0)
            {
                $('#arrow_left').attr('src','img/encod_left_enable.png');
            }
            else
            {
                $('#arrow_left').attr('src','img/encod_left_disable.png');
            }
        },
  
    };

    $('#slider').serialScroll(scrollOptions);
	
    $('#arrow_left').click(function()
    {
        $('.scrollContainer').trigger( 'prev' );
    });
	
    $('#arrow_right').click(function()
    {
        $('.scrollContainer').trigger( 'next' );
    });
	
	
    $('#submit').click(function(){
        $('#tab_'+(tab_idx+1)).removeClass('disabled').addClass('enabled');
        $('#arrow_right').attr('src','img/encod_right_enable.png');
    });
	
    $('.tabs li').click(function(el)
    {
        id = $(this).attr('id').substr(4);
        $('.scrollContainer').trigger( 'goto', [parseInt(id)] );
    });
});