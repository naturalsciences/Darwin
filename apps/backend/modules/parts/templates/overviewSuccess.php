<?php slot('title', __('Parts overview'));  ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'individual'=> $individual, 'mode' => 'parts_overview', 'view' => $view) );?>

<div>
<ul id="error_list" class="error_list" style="display:none">
  <li></li>
</ul>
</div>

  <?php include_partial('overviewTable', array('parts' => $parts, 'codes' => $codes, 'individual'=> $individual,'is_choose'=>false, 'view' => $view)); ?>

<br />
<script  type="text/javascript">

function addError(html)
{
  $('ul#error_list').find('li').text(html);
  $('ul#error_list').show();
}

function removeError()
{
  $('ul#error_list').hide();
  $('ul#error_list').find('li').text(' ');
}

$(document).ready(function () { 
  $('body').duplicatable({duplicate_href: '<?php echo url_for('specimen/confirm');?>'});

  $('body').catalogue({}); 
  $("a.row_delete").click(function()
  {
    if(confirm($(this).attr('title')))
    {
      currentElement = $(this);
      $.ajax({
        url: $(this).attr('href'),
        success: function(html)
        {
          if(html == "ok" )
          {
            currentElement.closest('tr').remove();
            if($('table.catalogue_table').find('tbody').find('tr.parts:visible').size() == 0)
            {
              $('table.catalogue_table').find('thead').hide();
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
      });
    }
    return false;
  });

});
</script>

<?php include_partial('specimen/specAfterTab');?>
