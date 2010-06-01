<?php slot('title', __('Edit Individuals'));  ?>

<?php include_partial('specimen/specBeforeTab', array('specimen' => $specimen, 'mode'=>'individuals_overview'));?>

<div class="widget_content">
<ul id="individuals_error" class="error_list" style="display:none">
  <li>Test</li>
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
      <th colspan="3">
      </th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($individuals as $i => $individual):?>
      <tr>
	<td>
	  <?php echo $individual->getType();?>
	</td>
	<td>
	  <?php echo $individual->getSex();?>
	</td>
	<td>
	  <?php echo $individual->getState();?>
	</td>
	<td>
	  <?php echo $individual->getStage();?>
	</td>
	<td>
	  <?php echo $individual->getSocialStatus();?>
	</td>
	<td>
	  <?php echo $individual->getRockForm();?>
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
  <tfoot>
    <tr>
      <td colspan='9'>
        <div class="add_spec_individual">
          <a href="<?php echo url_for('individuals/edit?spec_id='.$specimen->getId());?>" id="add_spec_individual"><?php echo __('Add Individual');?></a>
        </div>
      </td>
    </tr>
  </tfoot>
</table>
<script  type="text/javascript">

$(document).ready(function () {
  $("a.row_delete").live('click', function(){
     if(confirm($(this).attr('title')))
     {
       currentElement = $(this);
       removeError($(this));
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
</script>
<?php include_partial('specimen/specAfterTab');?>