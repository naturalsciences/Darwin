<?php if($form->isValid()):?>
  <?php if(isset($imports) && $imports->count() != 0 && isset($orderBy) && isset($orderDir) && isset($currentPage) && isset($is_choose)):?>
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
            <th></th>
            <th>
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=name'.( ($orderBy=='name' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
                <?php echo __('Collection');?>
                <?php if($orderBy=='name') echo $orderSign ?>
              </a>
            </th>
            <th>
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=filename'.( ($orderBy=='filename' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
                <?php echo __('Filename');?>
                <?php if($orderBy=='filename') echo $orderSign ?>
              </a>
            </th>
            <th>
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=state'.( ($orderBy=='state' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
                <?php echo __('Status');?>
                <?php if($orderBy=='state') echo $orderSign ?>
              </a>
            </th>  
            <th class="datesNum">
              <a class="sort" href="<?php echo url_for($s_url.'&orderby=updated_at'.( ($orderBy=='updated_at' && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
                <?php echo __('Last modification');?>
                <?php if($orderBy=='updated_at') echo $orderSign ?>
              </a>
            </th>                      
            <th><?php echo __("Progression") ; ?></th>
            <th><?php echo __("Possible actions") ; ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($imports as $import):?>
            <tr class="rid_<?php echo $import->getId(); ?>">
              <td></td>
              <td><?php echo $import->Collections->getName();?></td>
              <td><?php echo $import->getFilename();?></td>
              <td><?php echo $import->getState();?></td>
              <td><?php echo $import->getLastModifiedDate(ESC_RAW);?></td>
              <td><?php echo $import->getCurrentLineNum().'/'.$import->getInitialCount() ;?></td>
              <td>
                <?php if ($sf_user->isAtLeast(Users::ENCODER) && $import->isEditableState()) : ?>
                  <?php echo link_to(image_tag('edit.png',array('title'=>'Edit import')),'staging/index?import='.$import->getId());?>
                <?php endif ; ?>                  
              </td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
    <?php include_partial('global/pager', array('pagerLayout' => $pagerLayout)); ?>
  <?php else:?>
    <?php echo __('No import Matching');?>
  <?php endif;?>
<?php else : ?>
<?php echo $form->renderGlobalErrors() ; ?>  
<?php endif ; ?>  
