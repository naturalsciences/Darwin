<?php slot('title', __('Specimen individuals overview'));  ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'mode'=>'individuals_overview'));?>

<div>
<ul id="error_list" class="error_list" style="display:none">
  <li></li>
</ul>
</div>

<table class="catalogue_table">
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
      <th colspan="4">
      </th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($individuals as $i => $individual):?>
      <tr class="spec_individuals">
	<td><?php echo $individual->getTypeFormated();?></td>
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
	  <?php echo link_to(image_tag('edit.png'),'individuals/edit?id='.$individual->getId(), array('title'=>__('Edit this individual')));?>
	</td>
	<td class="row_delete">
	  <?php echo link_to(image_tag('remove.png'),'catalogue/deleteRelated?table=specimen_individuals&id='.$individual->getId(), array('class'=>'row_delete', 'title'=>__('Are you sure ?')));?>
	</td>
	<td>
	  <?php echo link_to(image_tag('slide_right_enable.png'),'parts/overview?id='.$individual->getId(), array('class'=>'part_detail_slide', 'title'=>__('Go to parts overview')));?>
	</td>
	<td>
	  <?php echo link_to(image_tag('slide_right_enable_new.png'),'parts/edit?indid='.$individual->getId(), array('class'=>'part_detail_slide', 'title'=>__('Edit a new part')));?>
	</td>
      </tr>
    <?php endforeach;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan='10'>
	<div class="add_spec_individual">
	  <a href="<?php echo url_for('individuals/edit?spec_id='.$specimen->getId());?>" id="add_spec_individual"><?php echo __('Add New');?></a>
	</div>
      </td>
    </tr>
  </tfoot>
</table>

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
  $("a.row_delete").click(function(){
     if(confirm($(this).attr('title')))
     {
       currentElement = $(this);
       removeError();
       $.ajax({
               url: $(this).attr('href'),
               success: function(html) {
		      if(html == "ok" )
		      {
			currentElement.closest('tr').remove();
			if($('table.catalogue_table').find('tbody').find('tr.spec_individuals:visible').size() == 0)
			{
			  $('table.catalogue_table').find('thead').hide();
			}
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
