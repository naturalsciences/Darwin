<?php if($form->isValid()):?>
<?php if(isset($items) && $items->count() != 0):?>
<div>
  <ul class="pager">
      <li>
	  <?php echo $form['rec_per_page']->renderLabel(); echo $form['rec_per_page']->render(); ?>
      </li>
      <?php $pagerLayout->display(); ?>
      <li class="nbrRecTot">
	<span class="nbrRecTotLabel">Total:&nbsp;</span><span class="nbrRecTotValue"><?php echo $pagerLayout->getPager()->getNumResults();?></span>
      </li>
  </ul>
</div>

<script type="text/javascript">
$(document).ready(function () 
  {
    $("#people_filters_rec_per_page").change(function ()
    {
      $.ajax({
	      type: "POST",
	      url: "<?php echo url_for('people/search?page='.$currentPage.'&is_choose='.$is_choose);?>",
	      data: $('#people_filter').serialize(),
	      success: function(html){
				      $(".search_content").html(html);
				    }
	    }
	    );
      $(".search_content").html('<?php echo image_tag('loader.gif');?>');
      return false;
    });
  });
</script>

<table class="results <?php if($is_choose) echo 'is_choose';?>">
  <thead>
    <tr>
      <th><?php echo __('Title');?></th>
      <th><?php echo __('Family Name');?></th>
      <th><?php echo __('Given Name');?></th>
      <th><?php echo __('Additional Names');?></th>
      <th><?php echo __('Life');?></th>
      <th><?php echo __('Activity');?></th>
      <th></th>
    <tr>
  </thead>
  <tbody>
  <?php foreach($items as $item):?>
    <tr class="rid_<?php echo $item->getId();?>">
      <td><?php echo $item->getTitle() ?></td>
      <td><?php echo $item->getFamilyName();?></td>
      <td><?php echo $item->getGivenName();?></td>
      <td><?php echo $item->getAdditionalNames() ?></td>
      <td class="datesNum">
	<?php echo $item->getBirthDateObject()->getDateMasked('em','Y');?> - 
	<?php echo $item->getEndDateObject()->getDateMasked('em','Y') ?>
      </td>
      <td class="datesNum">
	<?php echo $item->getActivityDateFromObject()->getDateMasked('em','Y');?> - 
	<?php echo $item->getActivityDateToObject()->getDateMasked('em','Y') ?>
      </td>
      <td class="<?php echo (! $is_choose)?'edit':'choose';?>">
          <?php if(! $is_choose):?>
	    <?php echo link_to(image_tag('edit.png'),'people/edit?id='.$item->getId());?>
          <?php else:?>
             <div class="result_choose"><?php echo __('Choose');?></div>
          <?php endif;?>
      </td>
    </tr>
  <?php endforeach;?>
  </tbody>
</table>
<?php else:?>
  <?php echo __('No Matching Items');?>
<?php endif;?>

<?php else:?>

<div class="error">
    <?php var_dump($sf_params->get('people_filters'));?>

    <?php echo $form->renderGlobalErrors();?>
    <?php echo $form['activity_date_to']->renderError(); ?>
    <?php echo $form['db_people_type']->renderError(); ?>
    <?php echo $form['is_physical']->renderError(); ?>
    <?php echo $form['activity_date_from']->renderError(); ?>
    <?php echo $form['family_name']->renderError(); ?>
</div>
<?php endif;?>