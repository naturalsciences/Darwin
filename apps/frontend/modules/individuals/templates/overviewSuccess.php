<?php slot('title', __('Specimen individuals overview'));  ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'mode'=>'individuals_overview'));?>

<div>
<ul id="error_list" class="error_list" style="display:none">
  <li></li>
</ul>
</div>

<table class="results">
  <thead style="<?php echo (count($individuals))?'':'display: none;';?>">
    <tr>
      <th>
	<?php echo __('Type');?>
      </th>
      <th>
	<?php echo __('Sex');?>
      </th>
      <th>
	<?php echo __('State');?>
      </th>
      <th>
	<?php echo __('Stage');?>
      </th>
      <th>
	<?php echo __('Social status');?>
      </th>
      <th>
	<?php echo __('Rock form');?>
      </th>
      <th colspan="3">
      </th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($individuals as $i => $individual):?>
      <tr>
	<td>
	  <?php echo $individual->getTypeFormated();?>
	</td>
	<td>
	  <?php echo $individual->getSexFormated();?>
	</td>
	<td>
	  <?php echo $individual->getStateFormated();?>
	</td>
	<td>
	  <?php echo $individual->getStageFormated();?>
	</td>
	<td>
	  <?php echo $individual->getSocialStatusFormated();?>
	</td>
	<td>
	  <?php echo $individual->getRockFormFormated();?>
	</td>
	<td>
	  <?php echo link_to(image_tag('edit.png'),'individuals/edit?spec_id='.$specimen->getId().'&individual_id='.$individual->getId());?>
	</td>
	<td class="row_delete">
	  <?php echo link_to(image_tag('remove.png'),'individuals/delete?spec_id='.$specimen->getId().'&individual_id='.$individual->getId(), array('class'=>'row_delete', 'title'=>__('Are you sure ?')));?>
	</td>
	<td>
	  <?php echo link_to(image_tag('slide_right_enable.png'),'parts/edit?id='.$individual->getId(), array('class'=>'part_detail_slide'));?>
	</td>
      </tr>
    <?php endforeach;?>
  </tbody>
</table>
<br/>
<div class="new_link">
  <a href="<?php echo url_for('individuals/edit?spec_id='.$specimen->getId());?>" id="add_spec_individual"><?php echo __('Add New');?></a>
</div>

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
  $("a.row_delete").live('click', function(){
     if(confirm($(this).attr('title')))
     {
       currentElement = $(this);
       removeError();
       $.ajax({
               url: $(this).attr('href'),
               success: function(html) {
		      if(html == "ok" )
		      {
			// Reload page
			location.reload();
		      }
		      else
		      {
			addError(html); //@TODO:change this!
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
</script>
<?php include_partial('specimen/specAfterTab');?>