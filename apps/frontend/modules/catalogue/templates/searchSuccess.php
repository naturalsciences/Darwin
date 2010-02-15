<?php if(isset($items) && $items->count() != 0):?>
  <?php
    if($orderDir=='asc')
      $orderSign = '<span class="order_sign_down">&nbsp;&#9660;</span>';
    else
      $orderSign = '<span class="order_sign_up">&nbsp;&#9650;</span>';
  ?>
  <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
  <?php include_partial('global/pager_info', array('form' => $searchForm, 'pagerLayout' => $pagerLayout)); ?>
  <div class="results_container">
    <table class="results <?php if($is_choose) echo 'is_choose';?>">
      <thead>
        <th colspan="2">
          <a class="sort" href="<?php echo url_for($s_url.'&orderby=name_indexed'.( ($orderBy=='name_indexed' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
            <?php echo __('Name');?>
            <?php if($orderBy=='name_indexed') echo $orderSign ?>
          </a>
        <?php if(isset($items[0]['lower_bound']) && isset($items[0]['upper_bound'])): ?>
          <th>
            <a class="sort" href="<?php echo url_for($s_url.'&orderby=lower_bound'.( ($orderBy=='lower_bound' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
              <?php echo __('Lower bound (My)');?>
              <?php if($orderBy=='lower_bound') echo $orderSign ?>
            </a>
          </th>
          <th>
            <a class="sort" href="<?php echo url_for($s_url.'&orderby=upper_bound'.( ($orderBy=='upper_bound' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
              <?php echo __('Upper bound (My)');?>
              <?php if($orderBy=='upper_bound') echo $orderSign ?>
            </a>
          </th>
        <?php endif;?>
        <th></th>
      </thead>
      <tbody>
        <?php foreach($items as $item):?>
          <tr class="rid_<?php echo $item->getId();?>">
            <?php
              $addedFormat = '';
              switch ($item->getStatus()) 
              {
                case 'invalid':
                  $addedFormat = ' ('.__('Invalid').')';
                  break;
                case 'deprecated':
                  $addedFormat = ' ('.__('Deprecated').')';
                  break;
              }
            ?>
            <td><?php echo image_tag('info.png',"title=info class=info");?></td>
            <td>
              <span class="item_name"><?php echo $item->getNameWithFormat();?><span class="invalid"><?php echo $addedFormat;?></span></span>
              <div class="tree">
              </div>
            </td>
            <?php if(isset($item['lower_bound']) && isset($item['upper_bound'])): ?>
              <td>
                <span><?php echo $item->getLowerBound();?></span>
              </td>
              <td>
                <span><?php echo $item->getUpperBound();?></span>
              </td>
            <?php endif;?>
            <td class="<?php echo (! $is_choose)?'edit':'choose';?>">
                <?php if(! $is_choose):?>
                  <?php echo link_to(image_tag('edit.png'),$searchForm->getValue('table').'/edit?id='.$item->getId());?>
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