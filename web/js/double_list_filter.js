var sfDoubleList =
{
  init: function(id, className)
  {
    form = sfDoubleList.get_current_form(id);

    callback = function() { sfDoubleList.submit(form, className) };

    if (form.addEventListener)
    {
      form.addEventListener("submit", callback, false);
    }
    else if (form.attachEvent)
    {
      var r = form.attachEvent("onsubmit", callback);
    }
  },

  move: function(srcId, destId)
  {
    var src = document.getElementById(srcId);
    var dest = document.getElementById(destId);
    for (var i = 0; i < src.options.length; i++)
    {
      if (src.options[i].selected)
      {
        dest.options[dest.length] = new Option(src.options[i].text, src.options[i].value);
        src.options[i] = null;
        --i;
      }
    }
  },

  submit: function(form, className)
  {
    var element;

    for (var i = 0; i < form.elements.length; i++)
    {
      element = form.elements[i];
      if (element.type == 'select-multiple')
      {
        if (element.className == className + '-selected')
        {
          for (var j = 0; j < element.options.length; j++)
          {
            element.options[j].selected = true;
          }
        }
      }
    }
  },

  get_current_form: function(el)
  {
    if ("form" != el.tagName.toLowerCase())
    {
      return sfDoubleList.get_current_form(el.parentNode);
    }

    return el;
  }
};

function sort_array(list_array)
{
  var list_options_text = new Array();
  var results = new Array();
  var i;
  var j;
  var selectedOption;
  for (i in list_array)
  {
    list_options_text[index] = list_array[i]["text"];
  }
  list_options_text.sort();
  for (i in list_options_text)
  {
    for (j in list_array)
    {
      if(list_options_text[i] == list_array[j]["text"])
      {
        results[i] = list_array[j];
      }
    }
  }
  return results;
}

function array_from_options(list)
{
  var results = new Array();
  $(list).find('option').each(function(index)
  {
    var list_options = new Array();
    list_options["value"] = $(this).val();
    list_options["text"] = $(this).text();
    list_options["selected"] = $(this).attr("selected");
    results[index] = list_options;
  });
  return results;
}

function filter_array(list_array, filter_text)
{
  var results = new Array();
  var i;
  var j=0;
  exp='.*'+filter_text+'.*';
  Expression = new RegExp(exp,'gi');
  for(i in list_array)
  {
    if(Expression.test(list_array[i]["text"]))
    {
      $results[j]=list_array[i];
      j++;
    }
  }
  return results;
}

function select_option_in_array(list_array, list_array_text, selectedFlag)
{
  var i;
  for (i in list_array)
  {
    if(list_array[i]["text"] == list_array_text)
    {
      list_array[i]["selected"] = selectedFlag;
    }
  }
  return list_array;
}

function list_options_from(element)
{
  var list_options = new Array();
  list_options["value"] = $(element).val();
  list_options["text"] = $(element).text();
  list_options["selected"] = $(element).attr('selected');
  return list_options;
}

function add_to_array(list_array, list_options)
{
  list_array.push(list_options);
  return list_array;
}

function remove_from_array(list_array, criteria, value_based)
{
  var i;
  for (i in list_array)
  {
    if (value_based)
    {
      if(list_array[i]["value"]==criteria)
      {
        list_array.splice(i,1);
      }
    }
    else
    {
      if(list_array[i]["text"]==criteria)
      {
        list_array.splice(i,1);
      }
    }
  }
  return list_array;
}

// Filter unassociated select box based on matching of text entered as a part of option text
function search_list(caller, list)
{
  $(list).find('option').each(function()
  {
    exp='.*'+$(caller).val()+'.*';
    Expression = new RegExp(exp,'gi');
    if(!Expression.test($(this).text()))
    {
      $(this).hide();
    }
    else
    {
      $(this).show();
    }
  });
}
// Reset the filter and redisplay all unassociated select options
function reset_list(caller, list)
{
  $(list).find('option').each(function()
  {
      $(this).show();
      $(caller).val('');
  });
}

