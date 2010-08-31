function addError(html, element)
{
  $(element).closest('.widget_content').find('.error_list li').text(html);
  $(element).closest('.widget_content').find('.error_list').show();
}

function removeError(element)
{
  $(element).closest('.widget_content').find('.error_list').hide();
  $(element).closest('.widget_content').find('.error_list li').text(' ');
}

$(document).ready(function () {
 $("a.link_catalogue").live('click', function(){
        scroll(0,0) ;
    $(this).qtip({
        content: {
            title: { text : $(this).attr('title'), button: 'X' },
            url: $(this).attr('href')
        },
        show: { when: 'click', ready: true },
        position: {
            target: $(document.body), // Position it via the document body...
            adjust: { y: 210 },
            corner: 'topMiddle' // ...at the topMiddle of the viewport
        },
        hide: false,
        style: {
            width: { min: 876, max: 1000},
            border: {radius:3},
            title: { background: '#C1CF56', color:'white'}
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
              $(this.elements.target).qtip("destroy");
              hideForRefresh(widget_parent.find('.widget_content'));
            }
        }
    });
    return false;
 });

 $("a.widget_row_delete").live('click', function(){
     if(confirm($(this).attr('title')))
     {
       currentElement = $(this);
       removeError($(this));
       $.ajax({
          url: $(this).attr('href'),
          success: function(html) {
		        if(html == "ok" )
		        {
			        //We are in a widget
			        if(currentElement.parent().hasClass('widget_row_delete'))
			        {
			          widget_parent = currentElement.closest('li.widget');
			          widget_parent.find('.widget_content').load(reload_url+'/widget/'+widget_parent.attr("id"));
			          hideForRefresh(widget_parent.find('.widget_content'));
			        }
			        else //We are into a qtip element
			        {
			          $('.qtip-button').click();
			        }
		        }
		        else
		        {
			        addError(html, currentElement); //@TODO:change this!
		        }
		      },
          error: function(xhr){
		        addError('Error!  Status = ' + xhr.status);
          }
        }
      );
    }
    return false;
 });

});

/** CATALOGUE LEVEL CHECKING ****/
(function($){
    $.catalogue_level = function(el, options){
        // To avoid scope issues, use 'base' instead of 'this'
        // to reference this class from internal events and functions.
        var base = this;
        
        // Access to jQuery and DOM versions of element
        base.$el = $(el);
        base.el = el;
        
        // Add a reverse reference to the DOM object
        base.$el.data("catalogue_level", base);
        
        base.init = function(){
            base.options = $.extend({},$.catalogue_level.defaultOptions, options);
            $(base.options['level_ref']).change(base.changeLevel);
            $(base.options['parent_ref']).bind('loadref',base.beforeSearchParent);
            $(base.options['parent_ref']).change(base.changeParent);
        };
        
        base.changeLevel = function(event)
        {
          event.preventDefault();
          if ($(this).val() == '')
          {
            base.$el.fadeOut();
          }
          else
          {
            base.$el.fadeIn();
            if (base.options['search_url'].length)
            {
              base.checkUpperLevel();
            }
          }
        };
        
        base.checkUpperLevel = function()
        {
          parent_item = $(base.options['parent_ref']); //base.$el.find('input[id$=\"_parent_ref\"]');
          $.ajax({
            url: base.options['search_url'] +'/level_id/' + $(base.options['level_ref']).val() + '/parent_id/' + $(base.options['parent_ref']).val() ,
            success: function(html) 
            {
              switch (html)
              {
                case "top":
                  $(base.options['button_ref']).fadeOut();
                  $(base.options['message_ref']).fadeOut();
                  $(base.options['parent_ref']).val("0");
                  $(base.options['parent_name']).html("-");
                  break;
                case "not ok":
                  $(base.options['button_ref']).fadeIn();
                  $(base.options['message_ref']).fadeIn();
                  break;
                default:
                  $(base.options['button_ref']).fadeIn();
                  $(base.options['message_ref']).fadeOut();
                  break;
              }
            }
          });
        }
        
        // Called just before the showing of the parent_ref chooser
        base.beforeSearchParent = function(event)
        {
          ref_level_id = $(base.options['level_ref']).val()
          if (ref_level_id.length)
          {
            ref_level_id = '/level/'+ref_level_id;
          }
          
          ref_caller_id = '';
          if (base.options['current_id'] != undefined && base.options['current_id'] != '')
          {
            ref_caller_id = '/caller_id/' + base.options['current_id'];
          }
          
          button_ref = $(base.options['button_ref']).find('.but_text');
          if(button_ref.data('href') == null)
          {
            button_ref.data('href', button_ref.attr('href'));
          }
          button_ref.attr('href', button_ref.data('href') + ref_level_id + ref_caller_id);
        };
  
        base.changeParent = function(event)
        {
          base.checkUpperLevel();
        };
        
        // Run initializer
        base.init();
    };
    
    $.catalogue_level.defaultOptions = {
        level_ref: 'select.catalogue_level',
        button_ref:  'div[id$=\"_parent_ref_button\"]',
        parent_name: 'div[id$=\"_parent_ref_name\"]',
        parent_ref: 'tr#parent_ref input[type="hidden"]',
        message_ref: 'div[id$=\"_parent_ref_warning\"]',
        current_id: '',
        search_url: ''
    };
    
    $.fn.catalogue_level = function(options){
        return this.each(function(){
            (new $.catalogue_level(this, options));
        });
    };
    
})(jQuery);

