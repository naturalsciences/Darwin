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
                top: $(document).scrollTop(), // Use document scrollTop so it's on-screen even if the window is scrolled
                left: 0,
                height: $(document).height(), // Span the full document height...
            })
            .appendTo(document.body) // Append to the document body
            .hide(); // Hide it initially
    });
}

function hideForRefresh(el)
{
  $(el).css({position: 'relative'})
  $(el).append('<div id="loading_screen" />')
}

function showAfterRefresh(el)
{
  $(el).children('#loading_screen').remove();
}

function trim(myString) 
{ 
  return jQuery.trim(myString);
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
  $(parent).find('input').val('');
  $(parent).hide();
}

function addPropertyValue()
{
  $.ajax(
  {
    type: "GET",
    url: $(this).attr('href')+ (0+$('.property_values tbody#property tr').length),
    success: function(html)
    {
      $('.property_values tbody#property').append(html);
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

function objectsAreSame(x, y) {
   var objectsAreSame = true;
   for(var propertyName in x) {
      if(x[propertyName] !== y[propertyName]) {
         objectsAreSame = false;
         break;
      }
   }
   return objectsAreSame;
}

$(document).ready(function () {
  
  $('.help_ico').live('mouseover mouseout', function(event) {
    if (event.type == 'mouseover') {
      $(this).attr('src','/images/help_icon_green.png');
    } else {
      $(this).attr('src','/images/help_icon_grey.png');
    }
  });
});
