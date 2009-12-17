<?php if($form->isValid()):?>
  <?php if(isset($expeditions) && $expeditions->count() != 0 && isset($orderBy) && isset($orderDir) && isset($currentPage) && isset($is_choose)):?>
    <div>
      <script type="text/javascript">
      $(document).ready(function () 
       {
         $("#searchExpedition_rec_per_page").change(function ()
         {
           $.ajax({
                   type: "POST",
                   url: "<?php echo url_for('expedition/search?orderby='.$orderBy.'&orderdir='.$orderDir.'&page='.$currentPage.'&is_choose='.$is_choose);?>",
                   data: $('#search_expedition').serialize(),
                   success: function(html){
                                           $(".search_results_content").html(html);
                                          }
                  }
                 );
           $(".search_content").html('<?php echo image_tag('loader.gif');?>');
           return false;
         });
       });
      </script>
      <ul class="pager">
         <li>
             <?php echo $form['rec_per_page']->renderLabel(); echo $form['rec_per_page']->render(); ?>
         </li>
         <?php $expePagerLayout->display(); ?>
         <li class="nbrRecTot">
           <span class="nbrRecTotLabel">Total:&nbsp;</span><span class="nbrRecTotValue"><?php echo $expePagerLayout->getPager()->getNumResults();?></span>
         </li>
      </ul>
    </div>
    <div class='is_choose_<?php echo $is_choose ?>'>
      <table class="results">
        <thead>
          <tr>
            <th>
              <a class="sort" href="<?php echo url_for('expedition/search?orderby=name'.
                                                       (($orderBy=='name' && $orderDir=='asc')?'&orderdir=desc':'').
                                                       '&page='.$currentPage.
                                                       '&is_choose='.$is_choose
                                                      );?>">
              <?php echo __('Name');?>
              <?php if($orderBy=='name'):?>
                <span class="order_sign_<?php echo (($orderDir=='asc')?'down':'up');?>">&nbsp;<?php echo (($orderDir=='asc')?'&#9660;':'&#9650;');?></span>
              <?php endif; ?>
              </a>
            </th>
            <th class="datesNum">
                <a class="sort" href="<?php echo url_for('expedition/search?orderby=expedition_from_date'.
                                                         (($orderBy=='expedition_from_date' && $orderDir=='asc')?'&orderdir=desc':'').
                                                         '&page='.$currentPage.
                                                         '&is_choose='.$is_choose
                                                        );?>">
                <?php echo __('From');?>
                <?php if($orderBy=='expedition_from_date'):?>
                  <span class="order_sign_<?php echo (($orderDir=='asc')?'down':'up');?>">&nbsp;<?php echo (($orderDir=='asc')?'&#9660;':'&#9650;');?></span>
                <?php endif; ?>
                </a>
            </th>
            <th class="datesNum">
                  <a class="sort" href="<?php echo url_for('expedition/search?orderby=expedition_to_date'.
                                                         (($orderBy=='expedition_to_date' && $orderDir=='asc')?'&orderdir=desc':'').
                                                         '&page='.$currentPage.
                                                         '&is_choose='.$is_choose
                                                        );?>">
                <?php echo __('To');?>
                <?php if($orderBy=='expedition_to_date'):?>
                  <span class="order_sign_<?php echo (($orderDir=='asc')?'down':'up');?>">&nbsp;<?php echo (($orderDir=='asc')?'&#9660;':'&#9650;');?></span>
                <?php endif; ?>
                </a>
            </th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($expeditions as $expedition):?>
            <tr class="rid_<?php echo $expedition->getId(); ?>">
              <td><?php echo $expedition->getName();?></td>
              <td class="datesNum"><?php echo $expedition->getExpeditionFromDateMasked();?></td>
              <td class="datesNum"><?php echo $expedition->getExpeditionToDateMasked();?></td>
              <td class="<?php echo (! $is_choose)?'edit':'choose';?>">
                <?php if(! $is_choose):?>
                  <?php echo link_to(image_tag('edit.png'),'expedition/edit?id='.$expedition->getId());?>
                <?php else:?>
                  <div id="result_choose"><?php echo __('Choose');?></div>
                <?php endif;?>
              </td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
  <?php else:?>
    <?php echo __('No Expedition Matching');?>
  <?php endif;?>
<?php else:?>
  <div class="error">
    <?php echo $form->renderGlobalErrors();?>
    <?php echo $form['name']->renderError() ?>
    <?php echo $form['from_date']->renderError() ?>
    <?php echo $form['to_date']->renderError() ?>
</div>
<?php endif;?>