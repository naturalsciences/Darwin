<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<?php if(isset($notion) && (($notion == 'method' || $notion =='tool'))):?>
  <form id="methods_and_tools_filter" class="search_form" action="<?php echo url_for('methods_and_tools/search?table='.$notion.((!isset($is_choose))?'':'&is_choose='.$is_choose));?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
    <div class="container">
      <table class="search hidden" id="<?php echo ($is_choose)?'search_and_choose':'search' ?>">
        <thead>
          <tr>
            <th><?php echo $form[$notion]->renderLabel() ?></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><?php echo $form[$notion]->render() ?></td>
            <td><input class="search_submit" type="submit" name="search" value="<?php echo __('Search'); ?>" /></td>
          </tr>
        </tbody>
      </table>
      <div class="search_results">
        <div class="search_results_content">
          <?php if($form->isValid()):?>
            <?php if(isset($methods_and_tools) && $methods_and_tools->count() != 0 && isset($orderBy) && isset($orderDir) && isset($currentPage) && isset($is_choose)):?>
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
                        <a class="sort" href="<?php echo url_for($s_url.'&orderby='.$notion.( ($orderBy==$notion && $orderDir=='asc') ? '&orderdir=desc' : '').'&page='.$currentPage);?>">
                          <?php echo __('Name');?>
                          <?php if($orderBy==$notion) echo $orderSign ?>
                        </a>
                      </th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($methods_and_tools as $method_and_tool):?>
                      <tr class="rid_<?php echo $method_and_tool->getId(); ?>">
                        <td><?php echo $method_and_tool->getName();?></td>
                        <td class="<?php echo (! $is_choose)?'edit':'choose';?>">
                          <?php if(! $is_choose):?>
                            <?php echo link_to(image_tag('edit.png',array('title'=>'Edit '.$notion)),'methods_and_tools/edit?id='.$method_and_tool->getId().'&notion='.$notion);?>
                            <?php echo link_to(image_tag('duplicate.png',array('title'=>'Duplicate '.$notion)),'methods_and_tools/new?duplicate_id='.$method_and_tool->getId().'&notion='.$notion);?>
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
              <?php echo __('No '.$notion.' matching');?>
            <?php endif;?>
          <?php else:?>
            <div class="error">
              <?php echo $form[$notion]->renderError() ?>
            </div>
          <?php endif;?>
        </div>
      </div>
      <div class='new_link'><a <?php echo !(isset($is_choose) && $is_choose)?'':'target="_blank"';?> href="<?php echo url_for('methods_and_tools/new?notion='.$notion) ?>"><?php echo __('New');?></a></div>
    </div>
  </form>
<?php else:?>
  <?php echo __('You need to precise if you wish to work on tools or methods');?>
<?php endif;?>
