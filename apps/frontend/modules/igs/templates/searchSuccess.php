<?php if($form->isValid()):?>
  <?php if(isset($igss) && $igss->count() != 0 && isset($orderBy) && isset($orderDir) && isset($currentPage) && isset($is_choose)):?>
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
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=ig_num'.( ($orderBy=='ig_num' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
                <?php echo __('I.G.');?>
                <?php if($orderBy=='ig_num') echo $orderSign ?>
              </a>
            </th>
            <th class="datesNum">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=ig_date'.( ($orderBy=='ig_date' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
                <?php echo __('I.G. creation date');?>
                <?php if($orderBy=='ig_date') echo $orderSign ?>
              </a>
            </th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($igss as $igs):?>
            <tr class="rid_<?php echo $igs->getId(); ?>">
              <td><?php echo $igs->getIgNum();?></td>
              <td class="datesNum"><?php echo $igs->getIgDateMasked(ESC_RAW);?></td>
              <?php if ($level > Users::REGISTERED_USER) : ?>              
              <td class="<?php echo (! $is_choose)?'edit':'choose';?>">
                <?php if(! $is_choose):?>
                  <?php echo link_to(image_tag('edit.png',array('title'=>'Edit IGS')),'igs/edit?id='.$igs->getId());?>
                  <?php echo link_to(image_tag('duplicate.png',array('title'=>'Duplicate IGS')),'igs/new?duplicate_id='.$igs->getId());?>
                <?php else:?>
                  <div class="result_choose"><?php echo __('Choose');?></div>
                <?php endif;?>
              </td>
              <?php else : ?>
                <td><?php echo link_to(image_tag('info.png', array("title" => __("View"))),'igs/view?id='.$igs->getId());?></td>
              <?php endif ; ?>   
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
  <?php else:?>
    <?php echo __('No I.G. Matching');?>
  <?php endif;?>
<?php else:?>
  <div class="error">
    <?php if(!$form['to_date']->hasError()): ?>
      <?php echo $form->renderGlobalErrors();?>
    <?php endif; ?>
    <?php echo $form['ig_num']->renderError() ?>
    <?php echo $form['from_date']->renderError() ?>
    <?php if(!$form['from_date']->hasError()): ?>
      <?php echo $form['to_date']->renderError() ?>
    <?php endif; ?>
  </div>
<?php endif;?>
