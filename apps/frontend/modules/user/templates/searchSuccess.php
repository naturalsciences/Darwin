<?php if($form->isValid()):?>
<?php if(isset($items) && $items->count() != 0):?>
  <?php
    if($orderDir=='asc')
      $orderSign = '<span class="order_sign_down">&nbsp;&#9660;</span>';
    else
      $orderSign = '<span class="order_sign_up">&nbsp;&#9650;</span>';
  ?>
  <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
  <?php include_partial('global/pager_info', array('form' => $form, 'pagerLayout' => $pagerLayout)); ?>
  <div class="results_container">
    <table class="results <?php if($is_choose) echo 'is_choose';?>">
      <thead>
        <tr>
          <th></th>
          <th class="hidden"></th>
          <th>
            <a class="sort" href="<?php echo url_for($s_url.'&orderby=title'.( ($orderBy=='title' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
              <?php echo __('Title');?>
              <?php if($orderBy=='title') echo $orderSign ?>
            </a>
          </th>
          <th>
	    <a class="sort" href="<?php echo url_for($s_url.'&orderby=family_name'.( ($orderBy=='family_name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
	      <?php echo __('Family Name');?>
	      <?php if($orderBy=='family_name') echo $orderSign ?>
	    </a>
          </th>
          <th>
	    <a class="sort" href="<?php echo url_for($s_url.'&orderby=given_name'.( ($orderBy=='given_name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
	      <?php echo __('Given Name');?>
	      <?php if($orderBy=='given_name') echo $orderSign ?>
	    </a>
          </th>
          <th>
	    <a class="sort" href="<?php echo url_for($s_url.'&orderby=additional_names'.( ($orderBy=='additional_names' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
	      <?php echo __('Additional Names');?>
	      <?php if($orderBy=='additional_names') echo $orderSign ?>
	    </a>
          </th>
          <th></th>
        <tr>
      </thead>
      <tbody>
        <?php foreach($items as $item):?>
          <tr class="rid_<?php echo $item->getId();?>">
            <td><?php echo image_tag('info.png',"title=info class=info");?></td>
            <td class="hidden item_name"><?php echo $item->getFormatedName();?></td>
            <td><?php echo $item->getTitle() ?></td>
            <td><?php echo $item->getFamilyName();?></td>
            <td><?php echo $item->getGivenName();?></td>
            <td><?php echo $item->getAdditionalNames() ?></td>
            <td class="<?php echo (! $is_choose)?'edit':'choose';?>">
                <?php if($is_choose):?>
                  <div class="result_choose"><?php echo __('Choose');?></div>
                <?php endif;?>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
<?php else:?>
  <?php echo __('No Matching Items');?>
<?php endif;?>

<?php else:?>

<div class="error">
    <?php echo $form->renderGlobalErrors();?>
    <?php echo $form['family_name']->renderError(); ?>
</div>
<?php endif;?>