var ref_element_id = null;
var ref_element_name = null;
var ref_level_id = '';
var ref_caller_id = '';

/*
(function($){
  $.button_ref = function(el, options){
    // To avoid scope issues, use 'base' instead of 'this'
    // to reference this class from internal events and functions.
    var base = this;
    
    // Access to jQuery and DOM versions of element
    base.$el = $(el);
    base.el = el;
    
    // Add a reverse reference to the DOM object
    base.$el.data("button_ref", base);
    
    base.init = function(){
      base.options = $.extend({},$.button_ref.defaultOptions, options);
      //base.$el.submit(base.onSubmit);
      // Put your initialization code here
    };
    
    base.deleteRecord = function (event)
    {
      event.preventDefault();
      if(confirm($(this).attr('title')))
      {
      }
    };
    // Run initializer
    base.init();
  };
  
  $.button_ref.defaultOptions = {
  };
  
  $.fn.button_ref = function(options){
    return this.each(function(){
      (new $.button_ref(this, options));
    });
  };
  
})(jQuery);

*/

function button_ref_modal(event)
{
    event.preventDefault();
    var last_position = $('body').scrollTop() ;              
    scroll(0,0) ;
    $(this).parent().parent().find('input[type="hidden"]').trigger({ type:"loadref"});
    $(this).qtip({
      id: 'modal',
      content: {
        text: '<img src="/images/loader.gif" alt="loading"> Loading ...',
        title: { button: true, text: $(this).parent().attr('title') },
                 ajax: {
                   url: $(this).attr('href'),
                          type: 'GET',
                          data: {with_js:1}
                 }
      },
      position: {
        my: 'center',
        at: 'center',
        target: $(document.body)
      },
      
      show: {
        ready: true,
        delay: 0,
        event: event.type,
        solo: true,
        modal: {
          on: true,
          blur: false
        },
      },
      hide: {
        event: 'close_modal',
        target: $('body')
      },
      events: {
        show: function () {
          ref_element_id = null;
          ref_element_name = null;
        },
        hide: function(event, api) {
          if(ref_element_id != null && ref_element_name != null)
          {
            parent_el = api.elements.target.parent().prevAll('.ref_name');                           
            parent_el.text(ref_element_name);         
            parent_el.prev().val(ref_element_id);
            api.elements.target.parent().prevAll('.ref_clear').removeClass('hidden').show();
            api.elements.target.find('.off').removeClass('hidden');
            api.elements.target.find('.on').addClass('hidden');
            parent_el.prev().trigger('change');
          }
          scroll(0,last_position) ;     
          api.destroy();
        }
      },
      style: 'ui-tooltip-light ui-tooltip-rounded'
    },event)
                   // And for IE of course...
                   .each(function(i) {
                     $.attr(this, 'oldtitle', $.attr(this, 'title'));
                   this.removeAttribute('title');
                   });
    return false;
}

function button_ref_clear(event)
{
  $(this).prevAll('.ref_name').text('-').show();
  $(this).prevAll('input').val('');
  $(this).next().find('.but_text .on').removeClass('hidden');
  $(this).next().find('.but_text .off').addClass('hidden');
  $(this).hide();
}
/*
$(document).ready(function () {
  $("a.but_text").live('click', function(event){
    event.preventDefault();
    var last_position = $('body').scrollTop() ;              
    scroll(0,0) ;
    $(this).parent().parent().find('input[type="hidden"]').trigger({ type:"loadref"});
    $(this).qtip({
      id: 'modal',
      content: {
        text: '<img src="/images/loader.gif" alt="loading"> Loading ...',
        title: { button: true, text: $(this).parent().attr('title') },
        ajax: {
          url: $(this).attr('href'),
          type: 'GET',
          data: {with_js:1}
        }
      },
      position: {
        my: 'center',
        at: 'center',
        target: $(document.body)
      },
      
      show: {
        ready: true,
        delay: 0,
        event: event.type,
        solo: true,
        modal: {
          on: true,
          blur: false
        },
      },
      hide: {
        event: 'close_modal',
        target: $('body')
      },
      events: {
        show: function () {
          ref_element_id = null;
          ref_element_name = null;
        },
        hide: function(event, api) {
          if(ref_element_id != null && ref_element_name != null)
          {
            parent_el = api.elements.target.parent().prevAll('.ref_name');                           
            parent_el.text(ref_element_name);         
            parent_el.prev().val(ref_element_id);
            api.elements.target.parent().prevAll('.ref_clear').removeClass('hidden').show();
            api.elements.target.find('.off').removeClass('hidden');
            api.elements.target.find('.on').addClass('hidden');
            parent_el.prev().trigger('change');
          }
          scroll(0,last_position) ;     
          api.destroy();
        }
      },
      style: 'ui-tooltip-light ui-tooltip-rounded'
    },event)
    // And for IE of course...
    .each(function(i) {
      $.attr(this, 'oldtitle', $.attr(this, 'title'));
      this.removeAttribute('title');
    });
    return false;
  });

  $('.ref_clear').live('click',function()
  {
    $(this).prevAll('.ref_name').text('-').show();
    $(this).prevAll('input').val('');
    $(this).next().find('.but_text .on').removeClass('hidden');
    $(this).next().find('.but_text .off').addClass('hidden');
    $(this).hide();
  });
  
});*/
