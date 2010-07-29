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
    $("li #"+field).find('span:first').attr('class','hidden') ;
    $("li #"+field).find('span:nth-child(2)').attr('class','show') ; 
    $('table.spec_results thead tr th#col_'+column).attr('class','hidden');        
    $('table.spec_results tbody tr').find('td#col_'+column).attr('class','hidden'); 
  }
  else
  {     
    $("li #"+field).find('span:first').attr('class','show') ;
    $("li #"+field).find('span:nth-child(2)').attr('class','hidden') ;        
    $('table.spec_results thead tr th#col_'+column).attr('class','show');     
    $('table.spec_results tbody tr').find('td#col_'+column).attr('class','show');    
  }  
}
