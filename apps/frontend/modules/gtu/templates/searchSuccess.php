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
          <th>
            <a class="sort" href="<?php echo url_for($s_url.'&orderby=code'.( ($orderBy=='code' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
              <?php echo __('Code');?>
              <?php if($orderBy=='code') echo $orderSign ?>
            </a>
          </th>
          <th><?php echo __('Location');?></th>
          <th class="datesNum">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=gtu_from_date'.( ($orderBy=='gtu_from_date' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
                <?php echo __('From');?>
                <?php if($orderBy=='gtu_from_date') echo $orderSign ?>
              </a>
          </th>
          <th class="datesNum">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=gtu_to_date'.( ($orderBy=='gtu_to_date' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
                <?php echo __('To');?>
                <?php if($orderBy=='gtu_to_date') echo $orderSign ?>
              </a>
          </th>
          <th></th>
      </thead>
      <tbody>
        <?php foreach($items as $item):?>
          <tr class="rid_<?php echo $item->getId();?>">
            <td><?php echo $item->getCode();?></td>
	    <td class="item_name"><?php echo $item->getName();?>

	    </td>
            <td class="datesNum"><?php echo $item->getGtuFromDateMasked();?></td>
            <td class="datesNum"><?php echo $item->getGtuToDateMasked();?></td>
            <td class="<?php echo (! $is_choose)?'edit':'choose';?>">
              <?php if(! $is_choose):?>
                <?php echo link_to(image_tag('edit.png'),'gtu/edit?id='.$item->getId());?>
              <?php else:?>
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
    <?php echo $form['code']->renderError() ?>
    <?php echo $form['gtu_from_date']->renderError() ?>
    <?php echo $form['gtu_to_date']->renderError() ?>
</div>
<?php endif;?>