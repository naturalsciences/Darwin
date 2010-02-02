<?php if($form->isValid()):?>
  <?php if(isset($igss) && $igss->count() != 0 && isset($orderBy) && isset($orderDir) && isset($currentPage) && isset($is_choose)):?>
    <div>
      <script type="text/javascript">
      $(document).ready(function () 
       {
         $("#searchIg_rec_per_page").change(function ()
         {
           $.ajax({
                   type: "POST",
                   url: "<?php echo url_for('igs/search?orderby='.$orderBy.'&orderdir='.$orderDir.'&page='.$currentPage.'&is_choose='.$is_choose);?>",
                   data: $('#search_form').serialize(),
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
         <?php $igPagerLayout->display(); ?>
         <li class="nbrRecTot">
           <span class="nbrRecTotLabel">Total:&nbsp;</span><span class="nbrRecTotValue"><?php echo $igPagerLayout->getPager()->getNumResults();?></span>
         </li>
      </ul>
    </div>
    <div class='is_choose_<?php echo $is_choose ?>'>
      <table class="results">
        <thead>
          <tr>
            <th>
              <a class="sort" href="<?php echo url_for('igs/search?orderby=ig_num'.
                                                       (($orderBy=='ig_num' && $orderDir=='asc')?'&orderdir=desc':'').
                                                       '&page='.$currentPage.
                                                       '&is_choose='.$is_choose
                                                      );?>">
              <?php echo __('I.G.');?>
              <?php if($orderBy=='ig_num'):?>
                <span class="order_sign_<?php echo (($orderDir=='asc')?'down':'up');?>">&nbsp;<?php echo (($orderDir=='asc')?'&#9660;':'&#9650;');?></span>
              <?php endif; ?>
              </a>
            </th>
            <th class="datesNum">
                <a class="sort" href="<?php echo url_for('igs/search?orderby=ig_date'.
                                                         (($orderBy=='ig_date' && $orderDir=='asc')?'&orderdir=desc':'').
                                                         '&page='.$currentPage.
                                                         '&is_choose='.$is_choose
                                                        );?>">
                <?php echo __('I.G. creation date');?>
                <?php if($orderBy=='ig_date'):?>
                  <span class="order_sign_<?php echo (($orderDir=='asc')?'down':'up');?>">&nbsp;<?php echo (($orderDir=='asc')?'&#9660;':'&#9650;');?></span>
                <?php endif; ?>
                </a>
            </th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($igss as $igs):?>
            <tr class="rid_<?php echo $igs->getId(); ?>">
              <td><?php echo $igs->getIgNum();?></td>
              <td class="datesNum"><?php echo $igs->getIgDateMasked();?></td>
              <td class="<?php echo (! $is_choose)?'edit':'choose';?>">
                <?php if(! $is_choose):?>
                  <?php echo link_to(image_tag('edit.png'),'igs/edit?id='.$igs->getId());?>
                <?php else:?>
                  <div class="result_choose"><?php echo __('Choose');?></div>
                <?php endif;?>
              </td>
            </tr>
          <?php endforeach;?>
        </tbody>
      </table>
    </div>
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