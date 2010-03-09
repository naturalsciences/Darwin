function getIdInClasses(el)
{
    var classes = $(el).attr("class").split(" ");
    for ( var i = 0; i < classes.length; i++ )
    {
        exp = new RegExp(".*id_([-]?[0-9]+)",'gi');
        var result = exp.exec(classes[i]) ;
        if ( result )
        {
            return result[1];
        }
    }
}

function getElInClasses(element,prefix)
{
    var classes = $(element).attr("class").split(" ");
    for ( var i = 0; i < classes.length; i++ )
    {
        exp = new RegExp(prefix+"(.*)",'gi');
        var result = exp.exec(classes[i]) ;
        if ( result )
        {
            return result[1];
        }
    }
}

function addFormError(form_el, message)
{
    if( $(form_el).is(':visible') )
    {
        $(form_el).qtip({
            content: message,
            show: { ready: true, when : { event: 'none'} },
            hide: { when: { event: 'change' } },
            style: { 
                width: 200,
                padding: 5,
                background: '#ec9593',
                color: 'black',
                border: {
                    width: 7,
                    radius: 5,
                    color: '#c36b70'
                },
                tip: 'bottomLeft',
                name: 'dark', // Inherit the rest of the attributes from the preset dark style
            },
            position: {
                corner: {
                    target: 'topRight',
                    tooltip: 'bottomLeft'
                }
            },
        });
        return true
    }
    else
    {
        return false;
    }
}

function removeAllQtip()
{
    var i = $.fn.qtip.interfaces.length; while(i--)
    {
            // Access current elements API
        var api = $.fn.qtip.interfaces[i];
            // Queue the animation so positions are updated correctly
        if(api && api.status.rendered && !api.status.hidden && !api.elements.target.is('.button'))
            api.destroy();
    };
}

function addBlackScreen()
{
    $(document).ready(function()
    {
        $('<div id=\"qtip-blanket\">')
            .css({
                position: 'absolute',
                top: $(document).scrollTop(), // Use document scrollTop so it's on-screen even if the window is scrolled
                left: 0,
                height: $(document).height(), // Span the full document height...
                width: '100%', // ...and full width
                opacity: 0.7, // Make it slightly transparent
                backgroundColor: 'black',
                zIndex: 5000  // Make sure the zIndex is below 6000 to keep it below tooltips!
            })
            .appendTo(document.body) // Append to the document body
            .hide(); // Hide it initially
    });
}

function hideForRefresh(el)
{
  $(el).css({position: 'relative'})
  $(el).append('<div id="loading_screen" />')
  $('#loading_screen').css({
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%', 
                height: '100%',
                opacity: 0.3,
                backgroundColor: 'black',
		cursor: 'wait',
                zIndex: 10000
            });
}

function showAfterRefresh(el)
{
  $(el).children('#loading_screen').remove();
}

function trim(myString) 
{ 
  return myString.replace(/^\s+/g,'').replace(/\s+$/g,'');
}

function showValues()
{
  $(this).parent().find('ul').slideDown();
  $(this).parent().find('.hide_value').show();
  $(this).hide();
  return false;
};

function hideValues()
{
  $(this).parent().find('ul').slideUp();
  $(this).parent().find('.display_value').show();
  $(this).hide();
  return false;
};


function clearPropertyValue()
{
  parent = $(this).closest('tr');
  nvalue='';
  $(parent).find('input').val(nvalue);
  $(parent).hide();
}

function addPropertyValue()
{
  $.ajax(
  {
    type: "GET",
    url: $(this).attr('href')+ (0+$('.property_values tbody tr').length),
    success: function(html)
    {
      $('.property_values tbody').append(html);
    }
  });
  return false;
}

function returnText(object)
{
  var obj = object.jquery ? object[0] : object;
  if(typeof obj.selectionStart == 'number')
  {
    return obj.value.substring(obj.selectionStart, obj.selectionEnd);
  }
  else if(document.selection)
  {
    // Internet Explorer
    obj.focus();
    var range = document.selection.createRange();
    if(range.parentElement() != obj) return false;

    if(typeof range.text == 'string')
    {
      return range.text;
    }
  }
  else
    return false;
}

function clearSelection(el)
{
  t = el.val();
  el.val('');
  el.val(t);
}

function result_choose ()
{
        el = $(this).closest('tr');
        ref_element_id = getIdInClasses(el);
        ref_element_name = el.find('span.item_name').text();
        $('.result_choose').die('click');
        $('.qtip-button').click();
}

$(document).ready(function () {

  $('.cancel_qtip').live('click',function () {
    $('.qtip-button').click();
  });

  o = {"dropShadows":false, "autoArrows":true, "firstOnClick":true, "delay":400};
  $('ul.main_menu').supersubs().superfish(o);
  $('ul.main_menu > li:not(.house):not(.exit)').append('<img class="highlight" src="/images/menu_expand.png" alt="" />');

});
