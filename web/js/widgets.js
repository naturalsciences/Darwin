(function($){
  $.widgets_screen = function(el, options){
    // To avoid scope issues, use 'base' instead of 'this'
    // to reference this class from internal events and functions.
    var base = this;
    
    // Access to jQuery and DOM versions of element
    base.$el = $(el);
    base.el = el;
    
    // Add a reverse reference to the DOM object
    base.$el.data("widgets_screen", base);
    
    base.init = function(){
      base.options = $.extend({},$.widgets_screen.defaultOptions, options);

      $('.board_col').sortable({
        connectWith: ['.board_col'],
        handle: '.widget_top_bar',
        update: base.changeOrder
      });
      
      $('.widget_refresh').live('click', base.refreshWidget);
      $('.widget_close').live('click', base.closeWidget);
      $('.widget_collection_button a').click(base.showWidgetCollection);
      $('.widget_collection_container a').click(base.addWidget);
      $('.widget_top_button img').live('click', base.showWidgetContent);
      $('.widget_bottom_button img').live('click',base.hideWidgetContent);
    };
    
    base.showWidgetContent = function()
    {
      elem = $(this).closest('.widget');
      base.changeStatus(elem.attr('id'), 'open');
      elem.find('.widget_content').slideDown();
      elem.find('.widget_bottom_button').slideDown();
      elem.find('.widget_top_button').slideUp();
    };
    
    base.hideWidgetContent = function()
    {
      elem = $(this).closest('.widget');
      base.changeStatus(elem.attr('id'), 'close');
      elem.find('.widget_content').slideUp();
      elem.find('.widget_bottom_button').slideUp();
      elem.find('.widget_top_button').slideDown();
    };
    
    var last_notified = {};
    
    base.changeOrder = function ()
    {
      var notification = {};
      $('.board_col').each(function(index,elem)
      {
        notification['col'+(index+1)] = String($(elem).sortable('toArray'));
      });

      if(! objectsAreSame(notification,last_notified))
        $.post(base.options['change_order_url'], notification );    
      last_notified = notification;
    };
    
    base.refreshWidget = function(event, element){
      event.preventDefault();
      if(element == null)
        element = $(this);
      widget = element.closest('.widget');
      hideForRefresh(widget.find('.widget_content'));
      widget.find('.widget_content').load(base.options['reload_url'] + '/widget/' + widget.attr('id') );
      return false;
    };

    base.closeWidget = function(event){
        event.preventDefault();
        widget = $(this).closest('.widget');
        
        base.changeStatus(widget.attr('id'), 'hidden');
        $('.widget_collection_container .no_more').addClass('hidden');
        $('#boardprev_'+widget.attr('id')).fadeIn();
        widget.remove();
        if($('.board_col li').length == 0)
          $('.no_more_wigets').removeClass('hidden');
        return false;
    };
    
    base.showWidgetCollection = function(event){
      event.preventDefault();
      if($('.widget_collection_container').is(":hidden"))
        {
            $('.widget_collection_container').slideDown();
            $('.widget_collection_button img').attr('src','/images/widgets_expand_up_button.png');
        }
        else
        {
            $('.widget_collection_container').slideUp();
            $('.widget_collection_button img').attr('src','/images/widget_expand_button.png');
        }
    };
    
    base.addWidget = function(event){
      event.preventDefault();
      var insertionDone = false;
      var widget_id = $(this).find('img').attr('alt');
      // Change Widget Status
      $.get($(this).attr('href'), function(html)
      {
        //Get last position
        $.get(base.options['position_url'] + '/widget/' + widget_id, function(response)
        {
          var position = jQuery.parseJSON(response);
          first_non_mandatory = $('.board_col:eq(' + (position.col_num-1) + ') li.widget a.widget_close:first');
          if(! first_non_mandatory.length)
            $('.board_col:eq(' + (position.col_num-1) + ')').append(html)

          //Insert Before last mandatory
          first_non_mandatory.closest('li.widget').before(html);
          base.changeOrder();
        });
      });
      
      $(this).parent().hide();
      if($('.widget_collection_container .widget_preview:visible').length == 0)
          $('.widget_collection_container .no_more').removeClass('hidden');
      $('.no_more_wigets').addClass('hidden');
    };
    
    base.changeStatus = function (id,status)
    {
      $.post(base.options['change_status_url'] + '/widget/' + id + '/status/' + status );
    };
    
    base.init();
  };
  


    
  $.widgets_screen.defaultOptions = {
    change_order_url: '',
    change_status_url: '',
    reload_url: '',
    position_url: ''
  };
  
  $.fn.widgets_screen = function(options){
    return this.each(function(){
      (new $.widgets_screen(this, options));
    });
  };

})(jQuery);