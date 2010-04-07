<?php if($form->isValid()):?>
  <?php if(isset($specimens) && $specimens->count() != 0 && isset($orderBy) && isset($orderDir) && isset($currentPage) && isset($is_choose)):?>
    <?php
      if($orderDir=='asc')
        $orderSign = '<span class="order_sign_down">&nbsp;&#9660;</span>';
      else
        $orderSign = '<span class="order_sign_up">&nbsp;&#9650;</span>';
    ?>
    <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
    <?php include_partial('global/pager_info', array('form' => $form, 'pagerLayout' => $pagerLayout)); ?>
    <div class="results_container">
      <table class="results">
        <thead>
          <tr>
            <th>
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=t.name'.( ($orderBy=='t.name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
                <?php echo __('Taxon');?>
                <?php if($orderBy=='t.name') echo $orderSign ?>
              </a>
            </th>
            <th>
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=t.level_ref'.( ($orderBy=='t.level_ref' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
                <?php echo __('Level');?>
                <?php if($orderBy=='t.level_ref') echo $orderSign ?>
              </a>
            </th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($specimens as $specimen):?>
            <tr class="rid_<?php echo $specimen->getId(); ?>">
              <td><?php echo $specimen->Taxonomy->getName();?></td>
              <td><?php echo $specimen->Taxonomy->Level->getLevelName();?></td>
              <td class="<?php echo (! $is_choose)?'edit':'choose';?>">
                <?php if(! $is_choose):?>
                  <?php echo link_to(image_tag('edit.png'),'specimen/edit?id='.$specimen->getId());?>
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
    <?php echo __('No Specimen Matching');?>
  <?php endif;?>
<?php else:?>
  <div class="error">
    <?php echo $form['taxon_name']->renderError() ?>
</div>
<?php endif;?>