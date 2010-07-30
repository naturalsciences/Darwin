function update_list(li)
{
  val = li.attr('class') ;
  if (val == 'check')
  {
    li.removeClass('check') ;
    li.addClass('uncheck') ; 
  }
  else
  {
    li.removeClass('uncheck') ;
    li.addClass('check') ; 
  }
}
function hide_or_show(li)
{
  field = li.attr('id') ;
  column = field.substr(3) ;
  val = li.attr('class') ;
  if(val == 'uncheck')
  {
    $("li #"+field).find('span:first').addClass('hidden');
    $("li #"+field).find('span:nth-child(2)').removeClass('hidden');
    $('table.spec_results thead tr th.col_'+column).addClass('hidden');
    $('table.spec_results tbody tr td.col_'+column).addClass('hidden');
  }
  else
  {
    $("li #"+field).find('span:first').removeClass('hidden');
    $("li #"+field).find('span:nth-child(2)').addClass('hidden');
    $('table.spec_results thead tr th.col_'+column).removeClass('hidden');
    $('table.spec_results tbody tr td.col_'+column).removeClass('hidden');
  }
}
